<a href="https://suitecrm.com">
  <img width="180px" height="41px" src="https://suitecrm.com/wp-content/uploads/2017/12/logo.png" align="right" />
</a>

# SuiteCRM 7.14.6

## New to this fork:

To start the dev server in your local environment, install [Docker](https://www.docker.com/) then run:

```
docker-compose up -d
docker-compose exec web composer install
```

Then navigate to `http://localhost:8080` to see it. 
The first time you do so, SuiteCRM kicks off an installation wizard.

use these database settings:
```
Host Name: db (the Docker service name)
Database Name: suitecrm
Database User: suitecrm
Database Password: suitecrm
```

and set the admin username, password, and email however you see fit.

## Added features

In addition to the Docker containerization dev-facing feature, this repo adds six user-facing features targeting real estate agents:

1. Properties module and mock MLS database
2. Transactions with list and pipeline view (Inquiry -> Showing -> Offer Made -> Under Contract -> Inspection/Appraisal -> Clear to Close)
3. Comparative Market Analysis generation (as .pdf)
4. Real Estate Hub (focused, responsive alternative to default dashboard with more modern style)
5. Commission calculator (adjustable in settings, calculated and totaled in Transaction pipeline)
6. Open House -> lead tracking flow (via QR code, print out, and web form)

These features are implemented using SuiteCRM's customization and extension features and intended to integrate seamlessly with the existing UI and functionality.

## Use of AI

These features were developed heavily leveraging AI.

My first pass at understanding the repo was generated via the [DeepWiki for SuiteCRM](https://deepwiki.com/SuiteCRM/SuiteCRM). 

I also looked at [SuiteCRM's Documentation](https://docs.suitecrm.com) and the associated [GitHub repo](https://deepwiki.com/SuiteCRM/SuiteDocs). 

I compared to the DeepWiki for [SuiteCRM-Core](https://github.com/SuiteCRM/SuiteCRM-Core) (v8 modernization) and ultimately chose to build off of SuiteCRM v7 because it was more complete and _less_ modern, which fit the xpirit of the assignment.

I followed up by asking questions of the AI there, Devin, and downloaded the docs into `_logs/DeepWiki` and my chats into `_logs` generally.

I challenged DeepWiki/Devin's understanding by having Claude explore the repo and the docs and create `CLAUDE.md` based on the result.

I then iterated on developing a PRD with various chats online using the uploaded document (see `features/plans` for some artifacts) and ultimately shaped one clear PRD in `.taskmaster/docs/prd.txt` (it was Gemini 2.5 Pro that produced the winner)

I used taskmaster (via Claude and taskmaster's MCP server) to break that PRD into tasks, dependencies, and subtasks.

Finally, I implemented the tasks in order via Claude code in a sort of iterative pair-programming fashion where Claude wrote the code and I helped provide debugging/troubleshooting guidance as we filled out the tasks.

---

[![Build Status](https://travis-ci.org/salesagility/SuiteCRM.svg?branch=hotfix)](https://travis-ci.org/salesagility/SuiteCRM)
[![codecov](https://codecov.io/gh/salesagility/SuiteCRM/branch/hotfix/graph/badge.svg)](https://codecov.io/gh/salesagility/SuiteCRM/branch/hotfix)
[![Gitter chat](https://badges.gitter.im/gitterHQ/gitter.png)](https://gitter.im/suitecrm/Lobby)
[![LICENSE](https://img.shields.io/github/license/suitecrm/suitecrm.svg)](https://github.com/salesagility/suitecrm/blob/hotfix/LICENSE.txt)
[![GitHub contributors](https://img.shields.io/github/contributors/salesagility/suitecrm)](https://github.com/salesagility/SuiteCRM/graphs/contributors)
[![Twitter](https://img.shields.io/twitter/follow/suitecrm.svg?style=social&label=Follow)](https://twitter.com/intent/follow?screen_name=suitecrm)

[Website](https://suitecrm.com) | 
[Demo](https://suitecrm.com/demo/) |
[Maintainers](https://salesagility.com) |
[Contributors](https://github.com/salesagility/SuiteCRM/graphs/contributors) |
[Community & Forum](https://suitecrm.com/suitecrm/forum) |
[Partners](https://suitecrm.com/about/about-us/partners/) |
[Extensions Directory](https://store.suitecrm.com/) |
[Translations](https://crowdin.com/project/suitecrmtranslations) | [Code of Conduct](https://docs.suitecrm.com/community/code-of-conduct/)

[SuiteCRM](https://suitecrm.com) is the award-winning open-source, enterprise-ready Customer Relationship Management (CRM) software application.

Our vision is to be the most adopted open source enterprise CRM in the world, giving users full control of their data and freedom to own and customise their business solution.

Try out a free fully working [SuiteCRM demo available here](https://suitecrm.com/demo/)

### Contribute [![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/salesagility/SuiteCRM/issues)

There are lots of ways to [contribute](https://docs.suitecrm.com/community/) to SuiteCRM

* [Submit bug](https://docs.suitecrm.com/community/raising-issues/) reports and help us [verify fixes](https://docs.suitecrm.com/community/contributing-code/test-pull-requests/) as they are pushed up
* Review and collaborate [source code](https://github.com/salesagility/SuiteCRM/pulls) changes
* Join and engage with other SuiteCRM users and developers on the [forums](https://suitecrm.com/suitecrm/forum)
* [Contribute bug fixes](https://docs.suitecrm.com/community/contributing-code/bugs/)
* Help [translate](https://docs.suitecrm.com/community/contributing-to-docs/contributing-to-translation/) language packs
* [Write and improve](https://docs.suitecrm.com/community/contributing-to-docs/) SuiteCRM documentation
* Signing CLA - Only needs to be done once for all PRs and contributions.


### Code Contributors

This project exists thanks to all the people who [contribute](https://github.com/salesagility/SuiteCRM/graphs/contributors) and more.
<a href="https://github.com/salesagility/SuiteCRM/graphs/contributors"><img src="https://opencollective.com/SuiteCRM/contributors.svg?avatarHeight=36&width=890&button=false" /></a>

You wanna buy the **core team** a coffee :coffee: or beer :beer:?
Then consider a small [donation](https://opencollective.com/SuiteCRM/contribute) to help fuel our activities :heart:

### Security ###

We take security seriously here at SuiteCRM so if you have discovered a security risk report it by
emailing [security@suitecrm.com](mailto:security@suitecrm.com). This will be delivered to the product team who handle security issues.
Please don't disclose security bugs publicly until they have been handled by the security team.

Your email will be acknowledged within 24 hours during the business week (Mon - Fri), and you’ll receive a more
detailed response to your email within 72 hours during the business week (Mon - Fri) indicating the next steps in
handling your report.

### Roadmap ### 

View the [Roadmap](https://suitecrm.com/roadmap/) and [LTS](https://suitecrm.com/lts/) for details on our planned features and future direction.

### Support ###

SuiteCRM is an open-source project. If you require help with support then please use our [support forum](https://suitecrm.com/suitecrm/forum/). By using the forums the knowledge is shared with everyone in the community. Our developer and community team members answer questions on the forum daily but it also allows the other members of the community to contribute. If you would like customisations to specifically fit your SuiteCRM needs then please visit the [website](https://suitecrm.com/).

### License [![AGPLv3](https://img.shields.io/github/license/suitecrm/suitecrm.svg)](./LICENSE.txt)

SuiteCRM is published under the AGPLv3 license.




