---
el: ".form-section"
title: 'Form Section'
---
#### Description
The form section is a portion of a form. It contains an optional heading and form inputs.

#### Variables
~~~
section_heading: short text / optional
section_heading_level: number (1-6) / optional
section_items:
  width: select / required / options: full, oneHalf, oneQuarter, threeQuarter, oneThird, twoThird, oneFifth, twoFifth, threeFifth, fourFifth
  title: short text / required
  placeholder: short text / optional
  type: input type / required
    - type allows for other requirements. See form elements.
  optional: boolean / optional
  info: see toggle tip
~~~
