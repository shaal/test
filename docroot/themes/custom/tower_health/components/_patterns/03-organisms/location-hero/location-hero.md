---
el: '.location-hero'
title: 'Location Hero'
---
#### Description
The location hero appears on a location detail page. It contains an optional image, a location type label, location name, location address lines 1 and 2, a directions url, and a set of phone numbers with a label and a number.

#### Variables
~~~
type: short text / required
name: short text / required
address_1: short text / required
address_2: short text / required
direction.url: href / required
phones:
  label: short text / required
  number: tel / required
img: optional
  src: media / required
  alt: short text / required
~~~
