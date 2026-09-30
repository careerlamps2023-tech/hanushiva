# Hanushiva Impex — Website

Static HTML site with a PHP/PHPMailer contact form. Structure follows skk.sg
(Home, About, Services, Quality Policy, Contact) with a Products page added.

## Files

| Path | Purpose |
|---|---|
| `index.html` | Hero, stats, who we are, vision/mission, 8 services, products, species, why-us, process, CTA |
| `about.html` | Company profile, pillars, why-us, company information table |
| `services.html` | 9 service cards, process timeline, FAQ accordion |
| `products.html` | 4 product lines, species cards, spec tables, ordering terms |
| `quality-policy.html` | 6 commitments, checkpoints, accountability FAQ |
| `contact.html` | Enquiry form, contact details, map — plain HTML like every other page |
| `404.html` | Error page (served by `404.php`, which adds the 404 status) |
| `send-mail.php` | Form endpoint — the only PHP file the browser touches |
| `includes/enquiry.php` | `send_enquiry()`: validation, honeypot, PHPMailer send |
| `config/mail-config.php` | **SMTP credentials — edit this** |
| `css/style.css` | Design system: tokens, components, placeholders, responsive |
| `js/main.js` | Mobile nav, sticky header, scroll reveal, stat counters, back-to-top |
| `vendor/` | PHPMailer 6.12 (Composer) |

## URLs

Pages are served without the `.html` extension via `mod_rewrite` in `.htaccess`:
`/about`, `/products`, `/contact`, `/thank-you`, and `/` for home. The files on disk
are still `about.html` etc. — only the URLs changed.

Three rules do it, in order:

1. `/index` or `/index.html` → 301 to the directory root, so home has one URL
2. any other `…​.html` → 301 to the clean form, so a page never has two live URLs
3. the clean URL is then rewritten **internally** to the `.html` file — no redirect, so
   the address bar keeps the tidy form

Redirect targets are taken from `THE_REQUEST` (`%1`) rather than a relative
substitution. That matters: a relative target in a per-directory `.htaccess` gets
expanded against the filesystem and leaks `C:/xampp/htdocs/...` into the `Location`
header. Using `%1` keeps it portable between a subfolder and the domain root.

Internal links are relative and extensionless (`href="about"`, `href="./"` for home), so
they work in both places without editing. `send-mail.php` and `main.js` redirect to
`thank-you`.

A fourth rule sends anything unmatched to `404.php`, which sets a real 404 status and
outputs `404.html`. That exists because `ErrorDocument` only accepts a path from the
**server** root — fine at `hanushiva.com/`, broken under `localhost/hanushiva.com/`. The
rewrite target is relative, so the custom 404 page works in both without editing.

**Requires** `mod_rewrite` enabled and `AllowOverride All` (or at least `FileInfo`) for
the site's directory. Without it every page 404s, so check this first after deploying.

## Products

Four product lines: **Sawn Timber, Plywood, Pinewood, Home Decor Items**, plus a
timber-species section (Nyatoh, Meranti, Kapur, Merbau, Chengal). `products.html` has a
deep-linkable section per line — `#sawn-timber`, `#species`, `#plywood`, `#pinewood`,
`#home-decor` — which the footer links to on every page.

Product copy was written for this site, informed by the species set on
kearhengtimber.com (the reference site organises by species rather than product type).
**Specification tables — thicknesses, widths, moisture-content figures, plywood grades,
lead times, loading ports — are plausible industry values, not the firm's real
published specs. Check every number before launch.**

**Local trade / PAN India.** The Services page has a dedicated section (`#domestic`),
and it is referenced on Home, About, the Products ordering table and the enquiry-form
subject list. The claim itself is yours; the supporting detail around it — road delivery,
collection ex-yard, part loads as well as full loads, the customer types listed — is
plausible placeholder text. Correct it to match how you actually operate.

## Images — supplied files

The nine photos in `my-files/` were cropped, resized, EXIF-stripped and compressed into
`images/`; the originals are untouched. Two caveats worth checking before launch:

- **Species mapping is unverified.** `images/species/grain-1…5.jpg` are labelled Nyatoh,
  Meranti, Kapur, Merbau, Chengal by appearance only. Confirm or replace.
- **Pinewood is resolved.** The original planed-board photo in those slots was a hardwood,
  not pine, so it was replaced with a genuine pine shot (knots, pale earlywood/latewood
  banding). The hardwood photo remains on the Services page as `services-operations.jpg`,
  where it makes no species claim.

The labelled species-fan image was not used: two of its panels are both labelled
"Merbau" and it carries a watermark.

`cta-bg.jpg` and `header-products.jpg` came from a 1400 px source, so they are below the
ideal 1920 px. They look fine but a larger original would sharpen them.

**Hero slideshow.** The homepage hero *slides* between every `<img class="hero-slide">`
inside `.hero-slides` — currently `images/hero-1.jpg` and `hero-2.jpg`, both 1920 × 1080,
cropped from the two Pexels timber-yard photos. Add another `.hero-slide` img to extend
the rotation; arrows, dots and direction all derive from however many exist, with no JS
change needed. Timing lives in two places: the 6 s autoplay interval in `js/main.js` and
the 0.85 s slide transition in `.hero-slide` (`css/style.css`). Prev/next arrows restart
the autoplay clock, autoplay pauses on a hidden tab, and the transition is off under
`prefers-reduced-motion`.

## Wood grain texture

`images/wood_pattern.webp` (your upload, 203 × 317) tiles with a visible seam, so
`images/wood_pattern_tile.webp` is generated from it — a 2 × 2 mirrored composition
(406 × 634) whose edges match exactly. That derived file is what the CSS uses; your
original is untouched. Regenerate it if you swap the pattern.

It appears in three places, always under a heavy colour wash so it reads as texture
rather than a yellow background (the footer is a solid green, no texture):

| Where | Wash | Rule |
|---|---|---|
| Alternating sections | white 88% | `.section-alt` |
| Page banners (4 pages with no header photo) | dark green 87–90% | `.hero-media-wood` + `.page-hero-wood` |
| Remaining image placeholders | off-white 70% | `.ph` / `.ph-dark` |

All three read off `--wood` and `--wood-tile` in `:root`. To make the grain fainter,
raise the wash alpha; to strengthen it, lower it. The four wood banners each carry an
HTML comment showing how to drop a real `header-*.jpg` photo in instead.

## Images

Photos live in `images/`, product tiles in `images/products/`, grain swatches in
`images/species/`. Everything is recompressed at JPEG quality 82, progressive, EXIF
stripped.

**Every content image is supplied — no placeholders remain on any public page.**
`images/products/pinewood.jpg` serves both the product tile and the Pinewood section,
the same way `sawn-timber.jpg` does for its line.

Optional, neither blocking:

- **One page banner left, 1920 × 600** — `header-services.jpg` for the Services page.
  Every other page now uses a photograph. That one page uses the tiled wood texture
  today, which looks deliberate rather than unfinished. Each one
  carries a numbered HTML comment in place showing exactly what to do, including removing
  the `page-hero-wood` / `hero-media-wood` classes so the lighter photo overlay
  (0.82 → 0.50) applies instead of the wood one. Keep the subject off-centre-right — the
  heading and breadcrumb sit on the left.
- **`og-image.jpg`** (1200 × 630) for WhatsApp / LinkedIn link previews.

A placeholder component is still in the CSS for any image slot added later — a dashed box
that prints its own path and required size:

```html
<div class="ph ph-4x3" data-ph="images/new-photo.jpg&#10;900 × 675 px"></div>
```

Aspect helpers: `.ph-16x9 .ph-4x3 .ph-3x2 .ph-1x1 .ph-4x5 .ph-wide`, plus `.ph-dark` for
dark backgrounds. Swap it for a real `<img>` when the artwork arrives.

**Logo.** `images/logo.png` (1967 × 648, transparent) sits in the header of every page,
rendered 56 px tall — 44 px on mobile — via `.logo-img`. Explicit `width`/`height`
attributes are set so the header does not shift while it loads.

The **footer uses `images/logo-light.png`**, generated from `logo.png`. The original is
43% charcoal ink, which measures 1.21:1 against the footer green — invisible. The
reversed version lifts the charcoal (gear + "IMPEX") to cream and lightens the
"HANUSHIVA" wordmark, taking both to 5.9:1 or better, while leaving the circular mark at
full strength because it sits on its own light tan disc. Rendered 62 px tall (56 px
mobile). **Regenerate it if the logo ever changes** — it is a derived file.

The old "HI" monogram lockup is gone; `.logo-mark` and `.logo-text` were deleted from the
CSS.

At 90 KB for a 170 px-wide slot the file is heavier than it needs to be; exporting a
~400 px-wide copy would cut it to roughly 15 KB with no visible difference.

## Icons & favicon

All icons are inline SVG (stroke-based, 24×24 viewBox, `currentColor`) — no icon font or
external library, so nothing extra to load. Reusable wrappers in `style.css`:
`.card-icon` (52 px rounded badge, inverts on card hover), `.info-ico` (contact list),
`.h3-ico` (inline icon before a heading), `.footer-contact` (icon-led footer list).

The favicon is `images/favicon.png` (your file, 100 × 100), referenced in every page's
`<head>` along with an `apple-touch-icon` for iOS home screens. It replaced the inline
SVG placeholder. A 512 × 512 version would render more sharply on high-DPI screens and
when saved to an Android home screen — the current file is fine for the browser tab.

## How the contact form works

Every page is static `.html`, including `contact.html`. PHPMailer is a PHP library, so
one server-side file is unavoidable — but it is only the endpoint, not the page:

```
contact.html  --POST-->  send-mail.php  -->  includes/enquiry.php  -->  PHPMailer  -->  SMTP
```

`send-mail.php` answers in one of two ways:

- **With JavaScript** (the normal path) — `main.js` intercepts the submit, posts via
  `fetch()`, and the endpoint replies with JSON `{ok, errors}`. The page never reloads,
  so a validation error leaves everything the visitor typed exactly where it was. The
  button shows "Sending…" and is disabled while in flight.
- **Without JavaScript** — the plain form POST goes to the same endpoint, which sends the
  mail and then 303-redirects to `contact.html?status=sent#enquiry` (or `status=error`).
  A few lines in `main.js` read that flag and show the matching banner.

Both banners live in `contact.html` as empty `hidden` shells (`#form-ok`, `#form-err`)
that the JS fills in. Error text is inserted with `textContent`, never `innerHTML`.

**The one trade-off:** on the no-JS path a server-side rejection cannot re-fill the form,
because a static page has nothing to render the old values with. The fields carry
`required`, `type="email"` and `maxlength`, so the browser catches most mistakes before
submitting — and anyone with JS on never hits this. If losing typed input in that edge
case ever matters more than having a `.html` extension, the old single-file
`contact.php` approach is the way back.

`send-mail.php` accepts POST only; a GET redirects away. Validation, the honeypot and
the header-injection guard all live in `includes/enquiry.php`, which `.htaccess` blocks
from direct web access.

## Contact form setup

1. **Set the SMTP credentials** in `config/mail-config.php`:
   - `smtp_host` — your mail host (currently `smtp.hostinger.com`; use `smtp.gmail.com`
     for Google Workspace, or whatever your hosting panel shows)
   - `smtp_user` — `info@hanushiva.com`
   - `smtp_pass` — replace `CHANGE_ME` (for Gmail/Workspace use an **App Password**)
   - `smtp_secure` / `smtp_port` — `ssl`/465 or `tls`/587

2. Enquiries are delivered to **info@hanushiva.com**. `Reply-To` is the visitor, so
   replying from the inbox reaches them directly, while `From` stays on your own
   domain so SPF/DMARC does not reject the mail.

3. Reinstall PHPMailer if `vendor/` goes missing:
   ```
   composer install
   ```
   PHP is not on this machine's PATH — use `C:\xampp\php\php.exe` with `composer.phar`.

4. Test at `http://localhost/hanushiva.com/`. XAMPP's PHP needs the `openssl`
   extension enabled for SMTP over SSL/TLS — uncomment `extension=openssl` in `php.ini`
   if sending fails.

## Fonts

Inter + Sora are loaded from Google Fonts. If you would rather self-host, drop the
woff2 files in `fonts/`, add `@font-face` rules and remove the two `<link>` tags from
each page's `<head>`.

## Naming

The site uses the short trading name **Hanushiva Impex** everywhere. The registered name
*Hanushiva Impex LLP* is kept only where it is a legal statement: the footer copyright
line on every page, the "is a Limited Liability Partnership registered…" sentences on
Home and About, and the Legal Name / Trade Name rows in the About page table (which
match the GST certificate). Change those too if you would rather drop the suffix
entirely.

## Content to replace

Company name and address come from the GST and DGFT certificates in the
project root and are accurate. Registration identifiers (IEC, GSTIN, PAN) were
deliberately removed from every page — the site says only "registered with the DGFT" and
"registered under GST in Delhi", with no numbers. Note that Indian law requires the GSTIN
to be displayed at the place of business, and it is normally shown on invoices; that is a
separate obligation from the website. The four product lines are as you
specified. **Everything else is placeholder copy** — the service list, statistics (15+
countries, 100+ vendors), all specification tables, loading ports, lead times, payment
terms and FAQ answers. Replace them with the firm's real offering before
going live.
