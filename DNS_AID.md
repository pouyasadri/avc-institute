# DNS for AI Discovery (DNS-AID) Configuration

To enable DNS-based agent discovery for **applyvipconseil.com**, you should add the following HTTPS/SVCB records to your DNS provider (e.g., Cloudflare, Route53).

## Recommended DNS Records

| Type | Name | Value |
| :--- | :--- | :--- |
| **HTTPS** | `_index._agents` | `1 applyvipconseil.com. alpn="h2,h3" port=443 well-known="/llms.txt"` |
| **HTTPS** | `_a2a._agents` | `1 applyvipconseil.com. alpn="h2,h3" port=443` |

### Raw BIND/Zone File Format

```dns
_index._agents.applyvipconseil.com. 3600 IN HTTPS 1 applyvipconseil.com. (
    alpn="h2,h3"
    port=443
    well-known="/llms.txt"
)

_a2a._agents.applyvipconseil.com. 3600 IN HTTPS 1 applyvipconseil.com. (
    alpn="h2,h3"
    port=443
)
```

## Security Recommendations

1.  **DNSSEC**: It is highly recommended to sign your public discovery zone with **DNSSEC**. This ensures that validating resolvers (like those used by AI agents) return authenticated data and prevents spoofing.
2.  **ALPN**: The `alpn` parameter helps agents negotiate the correct protocol (HTTP/2 or HTTP/3) before connecting.
3.  **Port**: Explicitly defining `port=443` ensures agents use the standard HTTPS port.

## Verification

Once added, you can verify the records using `dig`:

```bash
dig HTTPS _index._agents.applyvipconseil.com
dig HTTPS _a2a._agents.applyvipconseil.com
```
