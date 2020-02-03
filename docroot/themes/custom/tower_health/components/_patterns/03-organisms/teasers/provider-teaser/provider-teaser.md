---
el: ".teaser"
title: 'Provider Teaser'
---
#### Description
The Provider teaser is a teaser view for a provider content type. It contains an optional image, name, specialties, focus, location, language, type (only displays if not doctor) and appointment link.

#### Variables
~~~
location:
  img:
    src: media / optional
    alt: short text / required if src provided
  details:
    name: short text / required
    specialties: short text / required
    focus: short text / optional
    location: short text / required
    language: short text / required
    rating: number / optional
    type: provider type display if not doctor / required
  link:
    href: href / required  
~~~
