# When a plan is blocked

`blocked` means implementation cannot proceed and only a human can unblock it. Stop the execute loop, set **Plan status** to `blocked`, summarize what was completed, and escalate clearly.

## Physically impossible for the AI (no retry helps)

- Real payment (credit card, billing account)
- Human login flows (Google OAuth, Apple Face/Touch ID, enterprise SSO)
- Secrets or credentials the AI cannot read or obtain
- Hardware-bound or 2FA-protected access

## WonderWP-specific blockers

- **Missing agent or skill** — the step names an agent/skill not found in `ai_resources/memory/agent-orchestration.md` after a fresh scan; the user must install or map the capability
- **Unavailable external dependency** — API, service, or environment the consumer project cannot reach from the current workspace
- **Unresolved product decision** — the plan assumes a choice the user has not made (scope, naming, breaking change) and implementation cannot proceed safely
- **Branch or repo state** — cannot checkout, merge, or push without destructive or unauthorized git operations

## What to do

1. Set plan **Plan status** to `blocked`.
2. Record **Result** on the current step explaining the blocker.
3. Update the orchestration run log row to `blocked`.
4. Optionally run **finalize** partially: outcome `blocked`, acceptance table for completed steps only, follow-ups listing what the human must do.
5. Do **not** run later steps or guess around the blocker.
