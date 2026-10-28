# Jenkins Node versions (Wonderful CI agent)

Node is installed on the Jenkins build agent via **nvm**. All versions share the same path prefix:

```text
/var/lib/jenkins/.nvm/versions/node/{VERSION}/bin
```

Example for Node 22:

```text
/var/lib/jenkins/.nvm/versions/node/v22.23.1/bin
```

Use that directory in the Jenkinsfile:

```groovy
env.PATH="/var/lib/jenkins/.nvm/versions/node/v22.23.1/bin:${env.PATH}";
sh 'node -v';
```

## Installed versions

| Version | Full `NODE_BIN_DIR` value |
| --- | --- |
| v9.1.0 | `/var/lib/jenkins/.nvm/versions/node/v9.1.0/bin` |
| v9.11.2 | `/var/lib/jenkins/.nvm/versions/node/v9.11.2/bin` |
| v12.22.7 | `/var/lib/jenkins/.nvm/versions/node/v12.22.7/bin` |
| v14.21.3 | `/var/lib/jenkins/.nvm/versions/node/v14.21.3/bin` |
| v16.14.2 | `/var/lib/jenkins/.nvm/versions/node/v16.14.2/bin` |
| v20.18.2 | `/var/lib/jenkins/.nvm/versions/node/v20.18.2/bin` |
| v22.23.1 | `/var/lib/jenkins/.nvm/versions/node/v22.23.1/bin` |

Verify on the agent if unsure:

```bash
ls /var/lib/jenkins/.nvm/versions/node/
```

## Map `package.json` → Jenkins path

Read `package.json` → `engines.node` and pick the **highest installed version** that satisfies the range. Prefer the latest patch listed above for that major line.

| `engines.node` (examples) | Jenkins path |
| --- | --- |
| `>=22 <23` | `/var/lib/jenkins/.nvm/versions/node/v22.23.1/bin` |
| `>=20 <21` | `/var/lib/jenkins/.nvm/versions/node/v20.18.2/bin` |
| `>=14 <15` | `/var/lib/jenkins/.nvm/versions/node/v14.21.3/bin` |
| `>=12 <13` | `/var/lib/jenkins/.nvm/versions/node/v12.22.7/bin` |
| `>=9 <10` | `/var/lib/jenkins/.nvm/versions/node/v9.11.2/bin` (prefer latest v9 patch) |

### No matching version

Node **16**, **18**, and other majors not in the table above are **not** currently installed on the Wonderful Jenkins agent. Either:

1. **Raise** the project `engines.node` to a supported major (recommended during maintenance), or
2. Ask ops to install the required version via nvm on the agent, then add it to this reference.

Do not invent patch numbers — only use versions listed here or confirmed on the agent.
