/**
 * Kanban Board JavaScript for Transactions Module
 * Implements drag-and-drop functionality
 */

(function($) {
    'use strict';
    
    var KanbanBoard = {
        
        init: function() {
            this.setupDragAndDrop();
            this.bindEvents();
        },
        
        /**
         * Setup drag and drop functionality
         */
        setupDragAndDrop: function() {
            var self = this;
            
            // Make cards draggable
            $('.kanban-card').each(function() {
                $(this).attr('draggable', 'true');
            });
            
            // Card drag start
            $('.kanban-card').on('dragstart', function(e) {
                e.originalEvent.dataTransfer.effectAllowed = 'move';
                e.originalEvent.dataTransfer.setData('text/html', this.innerHTML);
                e.originalEvent.dataTransfer.setData('card-id', $(this).data('id'));
                e.originalEvent.dataTransfer.setData('original-stage', $(this).data('stage'));
                $(this).addClass('dragging');
            });
            
            // Card drag end
            $('.kanban-card').on('dragend', function(e) {
                $(this).removeClass('dragging');
                $('.kanban-cards').removeClass('drag-over');
            });
            
            // Column drag over
            $('.kanban-cards').on('dragover', function(e) {
                if (e.preventDefault) {
                    e.preventDefault(); // Allows us to drop
                }
                e.originalEvent.dataTransfer.dropEffect = 'move';
                $(this).addClass('drag-over');
                return false;
            });
            
            // Column drag enter
            $('.kanban-cards').on('dragenter', function(e) {
                $(this).addClass('drag-over');
            });
            
            // Column drag leave
            $('.kanban-cards').on('dragleave', function(e) {
                $(this).removeClass('drag-over');
            });
            
            // Drop
            $('.kanban-cards').on('drop', function(e) {
                if (e.stopPropagation) {
                    e.stopPropagation(); // Stops some browsers from redirecting
                }
                e.preventDefault();
                
                var cardId = e.originalEvent.dataTransfer.getData('card-id');
                var originalStage = e.originalEvent.dataTransfer.getData('original-stage');
                var newStage = $(this).closest('.kanban-column').data('stage');
                
                // Don't do anything if dropped in same column
                if (originalStage === newStage) {
                    $(this).removeClass('drag-over');
                    return false;
                }
                
                // Find the card element
                var $card = $('.kanban-card[data-id="' + cardId + '"]');
                
                // Check if this column has the empty message
                var $emptyMessage = $(this).find('.kanban-empty');
                if ($emptyMessage.length > 0) {
                    $emptyMessage.remove();
                }
                
                // Move the card to new column
                $(this).append($card);
                $card.data('stage', newStage);
                
                // Update the stage via AJAX
                self.updateCardStage(cardId, newStage, originalStage);
                
                // Update the original column if it's now empty
                var $originalColumn = $('.kanban-column[data-stage="' + originalStage + '"] .kanban-cards');
                if ($originalColumn.children('.kanban-card').length === 0) {
                    $originalColumn.html('<div class="kanban-empty">No transactions in this stage</div>');
                }
                
                $(this).removeClass('drag-over');
                return false;
            });
        },
        
        /**
         * Update card stage via AJAX
         */
        updateCardStage: function(cardId, newStage, originalStage) {
            var self = this;
            
            // Show loading indicator
            SUGAR.ajaxUI.showLoadingPanel();
            
            $.ajax({
                url: 'index.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    module: 'Opportunities',
                    action: 'updateStage',
                    opportunity_id: cardId,
                    new_stage: newStage,
                    to_pdf: true
                },
                success: function(response) {
                    SUGAR.ajaxUI.hideLoadingPanel();
                    
                    try {
                        // Check if response is already an object (jQuery might have parsed it)
                        var result = typeof response === 'object' ? response : JSON.parse(response);
                        
                        if (result.success) {
                            // Update statistics
                            self.updateColumnStats(originalStage);
                            self.updateColumnStats(newStage);
                            
                            // Show success message
                            self.showNotification('Transaction moved successfully', 'success');
                        } else {
                            // Revert the move
                            self.revertMove(cardId, originalStage);
                            self.showNotification(result.message || 'Failed to update transaction', 'error');
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        console.error('Response was:', response);
                        // Revert the move
                        self.revertMove(cardId, originalStage);
                        self.showNotification('An error occurred while updating', 'error');
                    }
                },
                error: function() {
                    SUGAR.ajaxUI.hideLoadingPanel();
                    
                    // Revert the move
                    self.revertMove(cardId, originalStage);
                    self.showNotification('Network error: Failed to update transaction', 'error');
                }
            });
        },
        
        /**
         * Revert card to original position
         */
        revertMove: function(cardId, originalStage) {
            var $card = $('.kanban-card[data-id="' + cardId + '"]');
            var $originalColumn = $('.kanban-column[data-stage="' + originalStage + '"] .kanban-cards');
            
            // Remove empty message if present
            $originalColumn.find('.kanban-empty').remove();
            
            // Move card back
            $originalColumn.append($card);
            $card.data('stage', originalStage);
            
            // Check if the column we removed from is now empty
            var currentStage = $card.data('stage');
            var $currentColumn = $('.kanban-column[data-stage="' + currentStage + '"] .kanban-cards');
            if ($currentColumn.children('.kanban-card').length === 0) {
                $currentColumn.html('<div class="kanban-empty">No transactions in this stage</div>');
            }
        },
        
        /**
         * Update column statistics
         */
        updateColumnStats: function(stage) {
            var $column = $('.kanban-column[data-stage="' + stage + '"]');
            var $cards = $column.find('.kanban-card');
            var count = $cards.length;
            var total = 0;
            
            $cards.each(function() {
                var amountText = $(this).find('.amount').text();
                var amount = parseFloat(amountText.replace(/[^0-9.-]+/g, ''));
                if (!isNaN(amount)) {
                    total += amount;
                }
            });
            
            // Update count badge
            $column.find('.badge').text(count);
            
            // Update total amount
            var formattedTotal = '$' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            $column.find('.stage-total').text(formattedTotal);
        },
        
        /**
         * Show notification message
         */
        showNotification: function(message, type) {
            var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            var icon = type === 'success' ? 'glyphicon-ok' : 'glyphicon-exclamation-sign';
            
            var $alert = $('<div class="alert ' + alertClass + ' alert-dismissible kanban-alert" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span></button>' +
                '<span class="glyphicon ' + icon + '"></span> ' + message +
                '</div>');
            
            $('#kanban-container').prepend($alert);
            
            // Auto-hide after 3 seconds
            setTimeout(function() {
                $alert.fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);
        },
        
        /**
         * Bind additional events
         */
        bindEvents: function() {
            // Card click to view details
            $('.kanban-card').on('click', function(e) {
                if (!$(e.target).hasClass('card-title') && !$(e.target).closest('.card-title').length) {
                    e.preventDefault();
                    var recordId = $(this).data('id');
                    window.open('index.php?module=Opportunities&action=DetailView&record=' + recordId, '_blank');
                }
            });
            
            // Touch support for mobile devices
            if ('ontouchstart' in window) {
                this.setupTouchSupport();
            }
        },
        
        /**
         * Setup touch support for mobile devices
         */
        setupTouchSupport: function() {
            var self = this;
            var currentCard = null;
            var currentOffset = {x: 0, y: 0};
            var originalPosition = null;
            
            $('.kanban-card').on('touchstart', function(e) {
                currentCard = $(this);
                var touch = e.originalEvent.touches[0];
                currentOffset.x = touch.pageX - currentCard.offset().left;
                currentOffset.y = touch.pageY - currentCard.offset().top;
                originalPosition = currentCard.position();
                
                currentCard.css({
                    position: 'fixed',
                    zIndex: 1000,
                    opacity: 0.8
                });
            });
            
            $(document).on('touchmove', function(e) {
                if (currentCard) {
                    e.preventDefault();
                    var touch = e.originalEvent.touches[0];
                    currentCard.css({
                        left: touch.pageX - currentOffset.x,
                        top: touch.pageY - currentOffset.y
                    });
                    
                    // Find drop target
                    var dropTarget = document.elementFromPoint(touch.pageX, touch.pageY);
                    $('.kanban-cards').removeClass('drag-over');
                    $(dropTarget).closest('.kanban-cards').addClass('drag-over');
                }
            });
            
            $(document).on('touchend', function(e) {
                if (currentCard) {
                    var touch = e.originalEvent.changedTouches[0];
                    var dropTarget = document.elementFromPoint(touch.pageX, touch.pageY);
                    var $dropColumn = $(dropTarget).closest('.kanban-cards');
                    
                    if ($dropColumn.length > 0) {
                        var cardId = currentCard.data('id');
                        var originalStage = currentCard.data('stage');
                        var newStage = $dropColumn.closest('.kanban-column').data('stage');
                        
                        if (originalStage !== newStage) {
                            // Remove empty message if present
                            $dropColumn.find('.kanban-empty').remove();
                            
                            // Move card
                            currentCard.css({
                                position: '',
                                zIndex: '',
                                opacity: '',
                                left: '',
                                top: ''
                            });
                            $dropColumn.append(currentCard);
                            currentCard.data('stage', newStage);
                            
                            // Update via AJAX
                            self.updateCardStage(cardId, newStage, originalStage);
                        } else {
                            // Return to original position
                            currentCard.css({
                                position: '',
                                zIndex: '',
                                opacity: '',
                                left: '',
                                top: ''
                            });
                        }
                    } else {
                        // Return to original position
                        currentCard.css({
                            position: '',
                            zIndex: '',
                            opacity: '',
                            left: '',
                            top: ''
                        });
                    }
                    
                    $('.kanban-cards').removeClass('drag-over');
                    currentCard = null;
                }
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        if ($('#kanban-container').length > 0) {
            KanbanBoard.init();
        }
    });
    
})(jQuery);