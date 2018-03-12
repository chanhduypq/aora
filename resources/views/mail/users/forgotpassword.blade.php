@component('mail::message')
    <?php
    $content = nl2br($email->content);
    $content = str_replace('::name::','user', $content);

    $link = URL::to('password/reset/' . $token);
    $url_reset_password = '<a href="'.$link.'" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3097d1;border-top:10px solid #3097d1;border-right:18px solid #3097d1;border-bottom:10px solid #3097d1;border-left:18px solid #3097d1" target="_blank">Reset Your Password Here</a>';
    $content = str_replace('::button_forgot_password::',$url_reset_password, $content);

    $content = str_replace('::link::',$link, $content);

    $url_logo = '<img src="'.URL::to('images/aora-logo.png').'">';
    $content = str_replace('::logo::',$url_logo, $content);
    
    echo $content;
    ?>
@endcomponent
