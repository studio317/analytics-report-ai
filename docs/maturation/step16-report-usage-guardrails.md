# Step 16 Report Usage Guardrails

## 1. Scope

This step addresses the Step 12 P1-4 range and payload-size controls and P1-5 cost and abuse guardrails for the MVP Report Builder.

No external API communication was performed. No credential value, Authorization header, or request body was recorded.

## 2. Maximum report period

The MVP report period is limited to 31 days.

The limit is managed by `ANALYTICS_REPORT_AI_MAX_REPORT_DAYS` and `analytics_report_ai_get_max_report_days()`.

The date range is counted inclusively:

- `2026-05-01` to `2026-05-31` is 31 days and is allowed.
- `2026-05-01` to `2026-06-01` is 32 days and is rejected.

The current period is validated before GA4 requests are made. The comparison period is calculated from the validated current period.

## 3. Comparison period behavior

When comparison is enabled, the comparison period is fetched separately from GA4.

This step keeps the existing comparison choices:

- no comparison,
- previous month,
- previous year.

The comparison period calculation is checked for a valid date range, but this step does not change the comparison algorithm.

## 4. Payload-size controls

The existing row limits remain unchanged:

- `daily_trend`: up to 31 rows,
- `top_pages`: up to 10 rows,
- `traffic_channels`: up to 10 rows,
- `traffic_sources`: up to 10 rows,
- `regional_trends`: up to 10 rows.

The new 31-day report period limit aligns with the existing `daily_trend` row limit and helps keep GA4 requests and OpenAI payloads manageable for MVP use.

## 5. AI generation double-submit guardrail

The AI generation form now uses a lightweight JavaScript guard. On submit, the generate button is disabled and repeated submits from the same form submission are prevented.

If JavaScript is unavailable, the server-side behavior remains the same as before. This is only a client-side misclick guard, not a quota or billing control.

## 6. Intentionally not implemented

This step intentionally does not implement:

- server-side rate limiting,
- per-user usage counters,
- daily or monthly quotas,
- OpenAI usage API integration,
- GA4 quota management,
- billing amount calculation,
- changes to the GA4 preset report set,
- changes to the OpenAI request flow,
- changes to the AI payload structure,
- changes to transient storage or expiration.

## 7. Future work

Future maturation should consider server-side throttling, usage counters, admin-visible usage history, daily/monthly limits, GA4 quota handling, and clearer operational guidance for production use.
