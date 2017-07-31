
This module adds a contact us to all pages.  It also keeps a record of the enquiry made.

To make it visible on the page,
add the folling to Page.ss (or the template for any other page type):

```html


<% if $ContactUsProcessingForm %>
    $SiteConfig.ContactUsFormThankYouMessage
<% else %>
    $Layout
    $ContactUsEnquiryForm
<% end_if %>

```

The rest of it should work automatically.
