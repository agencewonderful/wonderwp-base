# Maintenance phases (modern)

| Phase | Focus |
| ----- | ----- |
| prepare | Maintenance branch, update `wwp-maintenance` |
| env | DB + languages, `.wonderful` submodule + install skills, activate plugin |
| backend | PHP policy, composer checks/update, plugins cycle, doctrine proxies, ledger compliance |
| frontend | Node policy, npm, build, smoke JS |
| devops | phpunit/cypress, Jenkins/exclude from ops templates, Jenkins wave PR, stale branches |
| backoffice | Admins, suspicious content, conditional client role |
| plugins | Sitemap, SecuPress, wwp-cron verify, conditional ledger leftovers |
| preprod | PR → develop, proxies, Jenkins, visual + critical smoke, DB update |
| prod | Merge + semver minor, proxies, Jenkins, smokes, translations |
| finalize | Client PDF FR, session finish, commit ledger |

Removed: media maintenance, Podio, IE, direct push develop, `maintenance_YYMMDD` tags.
