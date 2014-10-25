  window.fbAsyncInit = function() {
    FB.init({
      appId      : '713527592065491',
      xfbml      : true,
      version    : 'v2.1'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  $(document).ready(function() {

    $(".fblogin").click(function(event) {
       FB.login(function(response) {
          if (response.status === 'connected') {
          // Logged into your app and Facebook.
          var vfbid;
          var vemail;
          var vname;
          var vtoken;

          FB.api('/me', function(response) {
              vfbid = response['id'];
              vemail = response['email'];
              vname = response['name'];
              vtoken = FB.getAuthResponse()['accessToken'];

              $.post('/fblogin/', {fbid: vfbid,email: vemail, name: vname, token: vtoken}, function(data) {
                //optional stuff to do after success
                if(data=="success")
                  window.location="/dashboard/";
              });
          });
          
          } else if (response.status === 'not_authorized') {
            // The person is logged into Facebook, but not your app.
          } else {
            // The person is not logged into Facebook, so we're not sure if
            // they are logged into this app or not.
          }
       }, {scope: 'public_profile,email,manage_pages,publish_actions,read_page_mailboxes,read_insights,manage_notifications'});
    });
  });