---
el: ".teaser--small.location"
title: 'Location Teaser, Small variant'
---
#### Description
The Location teaser small variant is a teaser view for a location content type. It contains location name, link to location node page, address, phone, fax number, and a directions button, additionally it contains a cta to view other providers at this location. It is used on the provider detail page.

#### Variables
~~~
location:
  name: short text / required
  address_rows: short text / required / multiple
  phones:
    label: short text / required
    number: short text / required
  cta:
    url: href / required  
~~~
