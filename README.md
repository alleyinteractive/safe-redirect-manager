# Colorado Public Radio

- [Project Overview](#project-overview)
- [New Environment Setup](#new-environment-setup)
  - [For Alley developers,](#for-alley-developers,)
  - [For everyone else,](#for-everyone-else)
- [Frontend Codebase (Irving)](#frontend-codebase)
  - [Starting Irving](#starting-irving)
  - [Branch Workflow](#branch-workflow)
  - [Updating Core Irving](#updating-core-irving)
- [Backend Codebase (WordPress)](#backend-codebase)
  - [Branch Workflow](#branch-workflow-1)

# Project Overview

cpr.org is a headless [Irving](https://github.com/alleyinteractive/irving) project.

**Local**
* [Frontend Irving](http://localhost:3001)
* [WP Admin](https://cpr.alley.test/wp-admin/) (alley:interactive)

**Staging**
* [Frontend Irving](https://cpr-staging.herokuapp.com)
* [WP Admin](http://content-staging.cpr.org/wp-admin/) - Creds in 1pass
* [Pantheon Dashboard](https://dashboard.pantheon.io/sites/abd2856d-7c34-4a5b-88be-f30ce0274247#staging)

**Production**
* [Frontend Irving](https://cpr-production.herokuapp.com/)
* [WP Admin](http://content.cpr.org/wp-admin/) - Creds in 1pass
* [Pantheon Dashboard](https://dashboard.pantheon.io/sites/abd2856d-7c34-4a5b-88be-f30ce0274247#production)

**Others**
* [DeployBot](https://alleyinteractive.deploybot.com/127908--CPR-Colorado-Public-Radio)
* [Frontend Repo](https://github.com/alleyinteractive/cpr-irving)
* [Backend Repo](https://github.com/alleyinteractive/cpr-wp) (this repo)

# New Environment Setup

## For Alley developers,
For Alley developers, the APM package takes care of everything.
`apm install cpr`

## For everyone else,
Coming Soon

# Frontend Codebase
The frontend codebase is a fork of Irving. It is hosted on Heroku.

## Starting Irving
Use `npm run dev` to start your local server. The application will automatically open http://localhost:3001

There are other commands available in [package.json](https://github.com/alleyinteractive/cpr-irving/blob/production/package.json#L6-L14). Refer to the official Irving docs for more information on what they do.

## Branch Workflow
1. Branch off of `production` (let's call the new branch `feature`)
1. Develop `feature`
1. Create a pull request to merge `feature` into `staging` and merge at will into `staging`
1. `staging` auto-deploys to https://staging.cpr.org
1. Verify your changes there ^
1. Pull request `feature` into `production` and code review :horse:
1. Merge `feature` into `production`
1. `production` auto-deploys to https://cpr.org

## Updating Core Irving
To keep this project updated, changes from Core Irving should often be merged in. This can be accomplished easily by merging from `irving-production`, which is a remote to Irving already setup as part of `apm install`.

**Commands**
* `git checkout production`
* `git checkout -b update-core-irving`
* `git merge irving-production`

Resolve any merge conflicts (if you do this often, it should not become overwhelming) and follow the branch workflow as outlined above.


# Backend Codebase
The backend is WordPress, and this repo lives in /wp-content/. It is hosted on Pantheon, and uses the Multidev setup exclusively (no need to deploy from dev to test to Live). Travis handles CI/CD along with DeployBot.

## Branch Workflow
1. Branch off of `production` (let's call the new branch `feature`)
1. Develop `feature`
1. Create a pull request to merge `feature` into `staging` and merge at will into `staging`
1. `staging` auto-deploys to https://content-staging.cpr.org
1. Verify your changes there ^
1. Pull request `feature` into `production` and code review :horse:
1. Merge `feature` into `production`
1. `production` auto-deploys to https://content.consensys.net/wp-admin
