---
el: ".provider-header"
title: 'Provider Header'
---
#### Description
The provider header appears at the top of a provider detail page and contains an optional image, name, leadership role, rating summary, and an optional request appointment CTA.

If a provider has open scheduling, then the call to action button says 'Make an Appointment Online', if they do not, then the button says 'Request an Appointment'.

#### Variables
~~~
name: title / required
lead_title: short text / optional
rating: number / optional
provider_video: video url / optional
img:
  src: media / optional
  alt: short text / required if src provided
appt_label: short text / optional
~~~
