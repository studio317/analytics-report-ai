# Step 17 Transient Payload Policy

## 1. Scope

This step addresses the Step 12 P1-3 transient data policy and P2-4 payload shape validation before OpenAI submission.

No external API communication was performed. No credential value, Authorization header, payload body, or OpenAI request body was recorded.

## 2. Current transient storage

After GA4 preset reports are fetched, Analytics Report AI converts the GA4 data into an AI payload and stores that payload in a WordPress transient.

The transient contains the reviewed AI payload shown on the Report Builder preview. It can include analytics data such as host name, page paths, traffic channels/sources, cities, summary metrics, and comparison differences. It does not intentionally include Google Access Token, OpenAI API Key, GA4 Property ID, WordPress user information, cookies, or IP addresses.

## 3. Key and expiration policy

The existing user-scoped transient key policy is maintained:

- key helper: `analytics_report_ai_get_payload_transient_key()`
- key shape: `analytics_report_ai_payload_{user_id}`
- expiration helper: `analytics_report_ai_get_payload_transient_expiration()`
- expiration: 30 minutes

This step does not change the transient key shape or expiration duration.

## 4. Payload validation

The MVP payload validator checks the minimum shape needed before storage and before OpenAI submission:

- payload is an array,
- `payload_version` matches `0.1.0-mvp`,
- `language` is `ja`,
- `report_type` is `ga4_summary`,
- required sections are arrays,
- row limits are not exceeded.

The expected payload version is managed by `ANALYTICS_REPORT_AI_PAYLOAD_VERSION` and `analytics_report_ai_get_payload_version()`.

## 5. Row limit validation

The row limits are:

- `daily_trend`: 31 rows,
- `top_pages`: 10 rows,
- `traffic_channels`: 10 rows,
- `traffic_sources`: 10 rows,
- `regional_trends`: 10 rows.

These match the current payload formatter limits and are checked again before OpenAI submission.

## 6. Failure handling

If a generated GA4 payload fails validation, it is not saved to the transient and the user sees a general conversion error.

If the transient payload is missing, expired, not an array, has an unexpected payload version, lacks required sections, or exceeds row limits, OpenAI is not called. The transient is deleted and the user is asked to fetch GA4 data again.

Validation details, payload contents, and request bodies are not displayed in the admin screen.

## 7. Intentionally not implemented

This step intentionally does not implement:

- payload encryption,
- switching payload storage from transient to another database table or option,
- payload history,
- payload hash-only storage,
- server-side usage quota,
- major payload structure changes,
- changes to GA4 preset reports,
- changes to OpenAI request behavior,
- transient expiration changes.

## 8. Future work

Future maturation should consider more complete payload schema validation, payload minimization by report type, shorter retention where practical, hash or reference-based submission flows, server-side usage controls, and optional cleanup tools for stale analytics payloads.
