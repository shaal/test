---
el: ".teaser.location"
title: 'Location Teaser'
---
#### Description
The Location teaser is a teaser view for a location content type. It contains an optional image, location name, link to location node page, address, phone, fax number, and a directions button.

#### Variables
~~~
location:
  img: 
    src: media / optional
    alt: short text / required if src provided
  details:
    name: short text / required
    address1: short text / required
    address2: short text / required
    phone: short text / required
    fax: short text / optional
  link:
    url: href / required  
~~~
