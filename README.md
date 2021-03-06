# Tower Health - Content Model

This is the development repository for the Tower Health site in Drupal 8. It contains the codebase and an environment to run the site for development.

## Table of Contents

* [Development Environment](#development-environment)
* [Getting Started](#getting-started)
* [How do I work on this?](#how-do-i-work-on-this)
* [Drupal Development](#drupal-development)
* [Deployment](#Deployment)
* [Styleguide Development](#styleguide-development)
* [Additional Documentation](#additional-documentation)

## Development Environment

The development environment is based on [palantirnet/the-vagrant](https://github.com/palantirnet/the-vagrant). To run the environment, you will need:

* Mac OS X >= 10.10. _This stack may run under other host operating systems, but is not regularly tested. For details on installing these dependencies on your Mac, see our [Mac setup doc [internal]](https://github.com/palantirnet/documentation/wiki/Mac-Setup)._
* [Composer](https://getcomposer.org)
  * Update to the newest version: `brew composer update`
* [virtualBox](https://www.virtualbox.org/wiki/Downloads) >= 5.0
* [ansible](https://github.com/ansible/ansible) `brew install ansible`
* [vagrant](https://www.vagrantup.com/) >= 2.1.0
* Vagrant plugins:
  * [vagrant-hostmanager](https://github.com/smdahlen/vagrant-hostmanager) `vagrant plugin install vagrant-hostmanager`
  * [vagrant-auto_network](https://github.com/oscar-stack/vagrant-auto_network) `vagrant plugin install vagrant-auto_network`

If you update Vagrant, you may need to update your vagrant plugins with `vagrant plugin update`.

## Getting Started

1. Clone the project from github: `git clone git@github.com:palantirnet/towerhealth.git`
1. From inside the project root, run:

  ```
    vagrant up
  ```
3. You will be prompted for the administration password on your host machine
4. Log in to the virtual machine (the VM): `vagrant ssh`
5. From within the VM, build and install the Drupal site: `phing install`
1. Visit your site at [towerhealth.local](http://towerhealth.local)

## How do I work on this?

You can edit code, update documentation, and run git commands by opening files directly from your host machine.

To run project-related commands other than `vagrant up` and `vagrant ssh`:

* SSH into the VM with `vagrant ssh`
* You'll be in your project root, at the path `/var/www/towerhealth.local/`
* You can run `composer`, `drush`, and `phing` commands from here

## Drupal Development

You can refresh/reset your local Drupal site at any time. SSH into your VM and then:

1. Download the most current dependencies: `composer install`
2. Rebuild your local CSS and Drupal settings file: `phing build`
3. Reinstall Drupal: `phing install`
5. ... OR run all phing targets at once: `phing install`

Additional information on developing for Drupal within this environment is in [docs/general/drupal_development.md](docs/general/drupal_development.md).

## Migrate Providers / Locations
Directory data is stored on the productions server in the public files directory. These files are updated from TowerHealth via an internal SFTP job.
You can pull down this data and run the migrations yourself by doing the following.

Inside vagrant
1. `vagrant ssh`
1. `phing build`
1. `exit`

Outside vagrant
1. Login in to Acquia and go to your account and the credentials tab.
1. Follow the instructions to download the Drush 9 site aliases for all of your sites.
1. Confirm that you can access the production site by running `drush @towerhealth.prod status`
1. Go to the root of this project.
1. Run the following:
  1. `drush rsync @towerhealth.prod:files/directory  ./docroot/sites/default/files/`

Running migrations
1. `vagrant ssh`
1. `drush mim --group="towerhealth_provider_base_group"`
1. `drush mim towerhealth_locations`
1. `drush mim towerhealth_doctors`
1. `drush mim towerhealth_ratings`

## Deployment

1. Create a pull request of develop against master.
1. In the pull request notes capture the tickets and PRs for work included.
1. [Create a release in github ](https://github.com/palantirnet/towerhealth/releases)
1. Checkout the release locally.
1. Run ``phing artifact.``
1. Clear cache on production: ```drush @towerhealth.prod cr```
1. Copy production to dev server.
1. Checkout dev server to new release. ex(artifact-0.19.0)
1. ```drush @towerhealth.dev cr```
1. ```drush @towerhealth.dev cim```
1. Run smoke test on dev server.
1. Backup database on production server.
1. ```drush @towerhealth.prod cr```
1. ```drush @towerhealth.prod cim```
1. Update the following settings:
1. Edit the [index](https://towerhealth.prod.acquia-sites.com/admin/config/search/search-api/index/acquia_search_index/edit) and unset `read only`. 
1. Run cron to reset acquia connector settings.

## Setup local search
The local search isn't setup out of the box a few settings need to be changed.
* Go to http://towerhealth.local/admin/config/search/search-api
* Enable the local development server if not enabled.
* Disable the Acquia Search API Solr server.
* Change the [acquia index to use the local server](http://towerhealth.local/admin/config/search/search-api/index/acquia_search_index/edit)
* Re-edit the index and turn off read only.
* Clear the [index and rebuild tracking information.](http://towerhealth.local/admin/config/search/search-api/index/acquia_search_index)
* Index the site.

## Additional Documentation

Project-specific:

* [Technical Approach](docs/technical_approach.md)

General:

* [Drupal Development](docs/general/drupal_development.md)

## Styleguide

The styleguide is built using [Emulsify](https://www.drupal.org/project/emulsify). Emulsify requires the styleguide to be embeded within the theme. We have created a symlink to the styleguide at /styleguide to avoid needing to navigate to docroot/themes/custom/tower_health.

### Emulsify documentation
* [Wiki](https://github.com/fourkitchens/emulsify/wiki/Orientation)
* [Install guide](https://github.com/fourkitchens/emulsify/wiki/Install-(Composer))
* [Drupal usage](https://github.com/fourkitchens/emulsify/wiki/Drupal-Usage)
* [Gulp config](https://github.com/fourkitchens/emulsify/wiki/Gulp-Config)

### How to use
* Serve the styleguide and watch for changes:
  * From your local machine (not inside of Vagrant), change to the /styleguide folder and install the styleguide:
    * `cd /styleguide`
    * `yarn install`
  * Now, run the styleguide:
    * `yarn start`
  * After yarn starts, visit the styleguide at http://localhost:3000
  * Deploy to gh-pages
    * `yarn deploy`
  * Compile css and assets
    * `yarn compile`

## Performance
* [Performance Audit](https://docs.google.com/spreadsheets/d/13FHaj15h-eoUAuXzIpsPzh1uTowO5g2NSzCHJSpugjA/edit#gid=0)
* [Performance Budget](https://docs.google.com/spreadsheets/d/1pKr3cJxcpQ6R88b-w2oBWSqCAs1-klD_HS_lpcu4yug/edit#gid=1700686844)
* [Performance testing](docs/performance_testing.md)


## Notes
    * chmod -R 777 docroot/sites/default/files

----
Copyright 2019 Palantir.net, Inc.
