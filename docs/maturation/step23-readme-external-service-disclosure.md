# Step 23 Readme External Service Disclosure

## 1. Scope

This step drafts the WordPress.org-style external service disclosure in `readme.txt`.

No production PHP, JavaScript, or CSS was changed. No GA4 API request, OpenAI API request, credential validation request, or other external API communication was performed. No credential value, API key value, Authorization header, full request body, or full payload body was recorded.

## 2. Readme section added

The following section was added to `readme.txt`:

- `== External Services ==`

The section explains that third-party services are contacted only when an administrator starts a report action, not merely by viewing plugin screens.

## 3. Google Analytics Data API disclosure

The readme now states that clicking `Fetch GA4 Data` sends requests to the Google Analytics Data API.

The disclosure covers:

- service URL: `https://analyticsdata.googleapis.com/`,
- GA4 property ID,
- Google Access Token in the Authorization header,
- selected date range,
- comparison setting and comparison date range,
- hostName filter,
- pagePath filter,
- required report metrics and dimensions,
- summary metrics,
- daily trend,
- top pages,
- traffic channels,
- traffic sources,
- city-level regional trends for Japan,
- data intentionally not included in the GA4 request body,
- the MVP/developer verification limitation of manual Google Access Token entry.

Official links included:

- Google APIs Terms of Service: `https://developers.google.com/terms`
- Google Privacy Policy: `https://policies.google.com/privacy`

## 4. OpenAI API disclosure

The readme now states that clicking `Generate AI Report` sends a request to the OpenAI API.

The disclosure covers:

- service URL: `https://api.openai.com/v1/responses`,
- OpenAI API Key in the Authorization header,
- selected model name,
- fixed system instructions,
- reviewed AI payload shown in Payload Preview,
- possible payload contents,
- information intentionally excluded from the AI payload,
- API credits or quota use,
- generated output as a user-reviewed draft.

Official links included:

- OpenAI Service Terms: `https://openai.com/policies/service-terms/`
- OpenAI Privacy Policy: `https://openai.com/policies/privacy-policy/`

## 5. Credential storage and payload review

The readme now briefly states that the MVP stores the Google Access Token and OpenAI API Key in the WordPress database as plugin settings, does not display saved credential values again, and needs redesign before public or multi-user use.

The readme also explains the Payload Preview flow:

- the full raw GA4 response is not sent to OpenAI,
- selected GA4 results are formatted into an AI payload,
- the payload is shown in Payload Preview,
- the payload is stored temporarily in a user-scoped transient,
- payload validation runs before transient storage and before OpenAI generation.

## 6. Intentionally left for later

This step intentionally does not update:

- plugin header version,
- `Stable tag`,
- `Tested up to`,
- changelog wording,
- OAuth flow,
- credential storage implementation,
- GA4 API request logic,
- OpenAI API request logic,
- AI payload structure,
- transient key, expiration, or validation policy.

Those metadata and packaging updates remain part of the later readme/header alignment work identified in Step 22.

## 7. URL verification note

The official service links were checked during this step. They should still be rechecked before WordPress.org submission because policy URLs and names can change over time.
