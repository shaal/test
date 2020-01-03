---
el: '.provider-details'
title: 'Provider Details'
---
#### Description
The Provider Details component is used on the provider detail page in the main area and the sidebar area. It is flexible to allow for various types of content. It contains an optional label, optional toggle-tip for the label, and a set of items.

The items can contain an optional label, an optional link, an optional button styled link, an optional title, optional text, an optional toggle tip for the text, optional meta information, and a specialized location teaser for locations. 

#### Variables
~~~
detail:
  sidebar: boolean / styles this when in a sidebar.
  label: short text / optional
  info: short text / optional
  info_id: string / required if info is filled
  item:
    label: short text / optional
    link: optional
      url: href / required if link
      label: short text / required if link
    buttonLink: optional
      url: href / required if buttonLink
      text: short text / required if buttonLink
    title: short text / optional
    text: short text / optional
    info: short text / optional
    info_id: string / required if info is filled
    meta: short text / optional
    location: see location teaser--small organism, optional
~~~
