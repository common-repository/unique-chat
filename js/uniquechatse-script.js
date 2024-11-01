<!-- begin UniqueChat code -->

  (function() {
    var se = document.createElement('script'); se.type = 'text/javascript'; se.async = true;
    se.src = 'https://storage.googleapis.com/uniqueicchat/js/'+uniquechatse_script_vars.widget_id+'.js';
    var done = false;
    se.onload = se.onreadystatechange = function() {
      if (!done&&(!this.readyState||this.readyState==='loaded'||this.readyState==='complete')) {
        done = true;
        /* Place your UniqueChat JS API code below */
        /* UniqueChat.allowChatSound(true); Example JS API: Enable sounds for Visitors. */
      }
    };
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(se, s);
  })();

<!-- end UniqueChat code -->