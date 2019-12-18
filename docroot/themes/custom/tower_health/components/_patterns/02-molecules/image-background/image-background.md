---
el: ".image-background"
title: 'Image Background'
---
#### Description
The Image background molecule is a stylized image within a circle with a offset color background. It contains positioning variants.

Current variants include green, trust blue, top right, and bottom left.

The image should ways be a square ratio, but the actual dimensions will vary per component.

#### Variables
~~~
img:
  src: media / required
  alt: text / required
classes: 
  color: select / required / options: trust, growth [default]
  positioning: select / required / options: top-left [default], bottom-left
~~~
