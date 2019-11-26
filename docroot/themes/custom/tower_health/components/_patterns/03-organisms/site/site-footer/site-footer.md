---
title: 'Site Footer'
---
#### Description
The Site footer appears on all standard site pages. It contains a logo, a social menu navigation, footer menu,  contact information, as well as a copyright notice and a details menu for privacy information.

#### Variables
~~~
logo:
  img_src: media / required
  img_alt: text / required
  img_url: url / required [always to site homepage]
footer_items: see molecule / required
footer_social:
  icons: multiple / required
    title: text / required
    svg: SVG Media / required
    url: url / required
footer_contact:
  address_1: text / required
  address_2: text / required
  phone: phone / required
footer_legal: text / required
footer_details:
  href: url / required
  title: text / required
~~~
