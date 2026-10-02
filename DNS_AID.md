# DNS for AI Discovery (DNS-AID)

Status for **applyvipconseil.com** (verified Oct 2026):

| Check | Status | Notes |
| :--- | :--- | :--- |
| HTTPS `_index._agents` (TYPE65) | **Live** | `1 applyvipconseil.com. alpn=h2,h3 port=443` |
| HTTPS `_a2a._agents` (TYPE65) | **Live** | same target / params |
| DNSSEC | **Live** | DS + DNSKEY present; Cloudflare DoH returns `AD=true` |
| HTTP agent index | App route | `/.well-known/agents-index.json` |
| `llms.txt` | Live | https://applyvipconseil.com/llms.txt |
| Homepage `Link` headers | Live | points to llms.txt, agents-index, api-catalog, sitemap |

Nameservers: Cloudflare (`ray.ns.cloudflare.com` / `eloise.ns.cloudflare.com`).

---

## What agents resolve today

```text
_index._agents.applyvipconseil.com.  HTTPS  1 applyvipconseil.com. alpn="h2,h3" port=443
_a2a._agents.applyvipconseil.com.    HTTPS  1 applyvipconseil.com. alpn="h2,h3" port=443
```

After resolving the host, agents should fetch:

1. `https://applyvipconseil.com/.well-known/agents-index.json`
2. `https://applyvipconseil.com/llms.txt`
3. `https://applyvipconseil.com/.well-known/api-catalog`

---

## Cloudflare records (already created)

In **DNS → Records**, confirm two HTTPS records:

| Type | Name | Priority | Target | Value |
| :--- | :--- | ---: | :--- | :--- |
| HTTPS | `_index._agents` | 1 | `applyvipconseil.com` | `alpn="h2,h3" port=443` |
| HTTPS | `_a2a._agents` | 1 | `applyvipconseil.com` | `alpn="h2,h3" port=443` |

Do **not** add a non-standard `well-known=` SvcParam — that older advice is not part of RFC 9460 / current DNS-AID draft-02. Point agents to the HTTP index instead.

DNSSEC: keep **DNSSEC enabled** in Cloudflare (already on for this zone).

---

## Optional upgrade (draft-02 SVCB)

IETF [draft-mozleywilliams-dnsop-dnsaid-02](https://datatracker.ietf.org/doc/draft-mozleywilliams-dnsop-dnsaid/) prefers **SVCB (TYPE64)** for `_index._agents`, with a TargetName that has a real TLS certificate (no underscores).

Only add this if you operate a dedicated agent-index host. For this site, the HTTP index on the apex is enough:

```dns
; Optional — only if you later host a dedicated index endpoint
_index._agents.applyvipconseil.com. 3600 IN SVCB 1 applyvipconseil.com. (
    alpn="h2,h3"
    port=443
)
```

Cloudflare UI: create record type **SVCB**, name `_index._agents`, priority `1`, target `applyvipconseil.com`, params `alpn="h2,h3"` and `port=443`. Keep the existing HTTPS records unless you intentionally replace them.

---

## Verification

```bash
# App + live HTTP/DNS checks
php artisan discovery:verify --base-url=https://applyvipconseil.com

# Local HTTP only
php artisan discovery:verify --skip-dns

# Manual DNS (macOS dig often needs TYPE65, not the word HTTPS)
dig TYPE65 _index._agents.applyvipconseil.com +short
dig TYPE65 _a2a._agents.applyvipconseil.com +short

# Or Cloudflare DoH (human-readable)
curl -s "https://cloudflare-dns.com/dns-query?name=_index._agents.applyvipconseil.com&type=HTTPS" \
  -H "accept: application/dns-json" | python3 -m json.tool
```

Expected HTTPS answer data:

```text
1 applyvipconseil.com. alpn=h2,h3 port=443
```

---

## After deploy

1. Deploy the app so `/.well-known/agents-index.json` is public (currently 404 on production until this ships).
2. Purge Cloudflare cache for `/llms.txt`, `/.well-known/*`, and homepage HTML.
3. Re-run `php artisan discovery:verify --base-url=https://applyvipconseil.com`.
