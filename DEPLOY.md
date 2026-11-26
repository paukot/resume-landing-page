# Deploying resume-landing-page

## Usage

| Task                             | Command                                                     |
| :------------------------------- | :---------------------------------------------------------- |
| Deploy the latest `master`       | push to `master` (CI) or `DEPLOY_BRANCH=master ./deploy.sh` |
| Deploy another branch            | `DEPLOY_BRANCH=my-branch ./deploy.sh`                       |
| See what runs                    | `./deploy.sh status`                                        |
| Follow logs                      | `./deploy.sh logs`                                          |
| Back up now (database + uploads) | `./deploy.sh backup`                                        |
| Copy backups to your machine     | `./deploy.sh pull-backups`                                  |
| Restore the database             | `./deploy.sh restore backups/db-<STAMP>-<label>.sql.gz`     |
| Restore uploaded images          | `./deploy.sh restore backups/files-<STAMP>-<label>.tar.gz`  |

Restores take a safety backup first, load the dump into a temporary file and swap it in only if that worked
(a broken dump leaves the current database in place; the previous file also stays as `database.sqlite.before-restore`).

A nightly backup in addition to the per-deploy one (server crontab of the deploy user):

```
0 3 * * * cd /var/www/resume-landing-page && ./backup.sh >> backups/cron.log 2>&1
```
