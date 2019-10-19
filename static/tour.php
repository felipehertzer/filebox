<?php

/*
+--------------------------------------------------------------------------
|   FileBox - File Hosting & Sharing Script (v1.5)
|   =======================================================================
|   by ScriptsApart
|   (c) 2010 ScriptsApart
|   http://www.scriptsapart.com
|   =======================================================================
|   Web: http://www.scriptsapart.com
|   Email: support@scriptsapart.com
+--------------------------------------------------------------------------
|   > Developed On: 25th December 2010
+--------------------------------------------------------------------------
*/

include("../init.php");
include("../user/header.php");
subheader('Take our website tour', '', '', 'tour'); ?>

    <div class='global'>
        <div id='main'>
            <div id='account-header'><p><?php echo $webtitle; ?> Tour</p></div>
            <table id='sitetour'>
                <tr>
                    <td><p><img src='../images/tour/editprofile.png'/><span id='more'>( Edit )</span> Easily edit and
                            manipulate information. Be it your profile or the files you have uploaded. Editing your
                            information is as easy as it could be.</p></td>
                    <td><p><img src='../images/tour/users.png'/><span id='more'>( Users )</span> We really care about
                            our users. That's why we have made the registration and login process a breeze for our
                            users.</p></td>
                    <td><p><img src='../images/tour/lovable.png'/><span id='more'>( Interface )</span> Just amazing
                            interface. It is both stylish and elegant at the same time. You will just love it's
                            simplicity.</p></td>
                </tr>
                <tr>
                    <td><p><img src='../images/tour/photo.png'/><span id='more'>( Photos )</span> Uploading and managing
                            photos is fun. <?php echo $webtitle; ?> generates automatic thumbnails of your uploaded
                            photos and runs an altogether different interface for downloading photos.</p></td>
                    <td><p><img src='../images/tour/privacy.png'/><span id='more'>( Security )</span> We love security
                            and this is the reason that your files will always be secured with us. Your files and
                            information are always safe with us.</p></td>
                    <td><p><img src='../images/tour/oneclick.png'/><span id='more'>( One Click )</span> Upload, manage &
                            download with just one click. Everything is perfectly done so that you don't need to do
                            anything extra.</p></td>
                </tr>
                <tr>
                    <td><p><img src='../images/tour/notes.png'/><span id='more'>( Notes )</span> Attach notes to your
                            files. Keep track of your files and attach any neccessary info to them. Its easy and safe as
                            the information is only visible to you.</p></td>
                    <td><p><img src='../images/tour/ads.png'/><span id='more'>( Ads )</span> Intelligent advertisement
                            network. Ads are shown to you based on your preferences and location.</p></td>
                    <td><p><img src='../images/tour/videos.png'/><span id='more'>( Videos )</span> Do you love videos?
                            If yes, then we have made a special preview section on the download page for uploaded
                            videos. Get a sneak peek to what's inside.</p></td>
                </tr>
            </table>
        </div>
    </div>

<?php include("../user/footer.php"); ?>