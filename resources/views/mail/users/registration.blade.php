@component('mail::message')
    <?php
    $content = nl2br($email->content);
    $content = str_replace('::name::',$user->name, $content);

    $url_verify = '<a href="'.URL::to('register/verify/' . $user->remember_token.'?email='.$user->email).'" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3097d1;border-top:10px solid #3097d1;border-right:18px solid #3097d1;border-bottom:10px solid #3097d1;border-left:18px solid #3097d1" target="_blank">VERIFY MY ACCOUNT ></a>';
    $content = str_replace('::button_verify::',$url_verify, $content);

    $url_logo = '<img src="'.URL::to('images/aora-logo.png').'">';
    $content = str_replace('::logo::',$url_logo, $content);

    $facebook_logo = '<a style="float:left;margin-right: 10px" href="#"><img style="width:30px;height:30px;" src="'.URL::to('images/ico-facebook.png').'"></a>';
    $content = str_replace('::facebook_link::',$facebook_logo, $content);

    $twitter_logo = '<a style="float:left;margin-right: 10px" href="#"><img style="width:30px;height:30px;" src="'.URL::to('images/ico-twitter.png').'"></a>';
    $content = str_replace('::twitter_link::',$twitter_logo, $content);

    $instagram_logo = '<a style="float:left;margin-right: 10px" href="#"><img style="width:30px;height:30px;" src="'.URL::to('images/ico-instagram.png').'"></a>';
    $content = str_replace('::instagram_link::',$instagram_logo, $content);
    echo $content;
    ?>
@endcomponent
