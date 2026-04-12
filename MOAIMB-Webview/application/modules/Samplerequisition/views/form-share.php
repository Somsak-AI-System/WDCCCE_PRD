<div id="modal-form-share" class="modal modal-center fade" role="dialog" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <!--Content-->
        <div class="modal-content" style="height:250px; overflow-y: scroll">
            <!--Body-->
            <div class="modal-body mb-0 p-0">
                <div class="embed-responsive z-depth-1-half form-share" style="height:100%">
                    <form id="form-share" method="post" action="">

                        <div class="share-dialog a-open">

                            <div class="link_copy">
                                <button type="button" class="btn btn-icon-default" id="copy-btn"><i class="ph-link fa-2x"></i>คัดลอกลิงค์</button>
                            </div>

                            <div class="popup_social-share-block">
                                <span class="sh_w">share with</span>
                                <ul class="social-share-link pop2">
                                    <li>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" onclick="window.open(this.href, 'facebook-share','width=500,height=400,top='+win_open_top+',left='+win_open_left);return false;">
                                            <i class="fa-brands fa-facebook fa-3x" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://social-plugins.line.me/lineit/share?url=<?php echo $url; ?>" onclick="window.open(this.href, 'line-it-share', 'width=500,height=400,top='+win_open_top+',left='+win_open_left);return false;">
                                            <i class="fa-brands fa-line fa-3x" style="color:green" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="http://twitter.com/share?url=<?php echo $url; ?>" onclick="window.open(this.href, 'twitter-share', 'width=500,height=400,top='+win_open_top+',left='+win_open_left);return false;">
                                            <i class="fa-brands fa-twitter fa-3x" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
        <!--/.Content-->

    </div>
</div>
<style>
    .share-button,
    .copy-link {
        padding-left: 30px;
        padding-right: 30px;
        background: #dbe5ff;
        font-size: 16px;
        border-radius: 15px;
        font-weight: bold;
        color: #444;
    }

    .share-button,
    .share-dialog {
        position: absolute;
        bottom: 0%;
        left: 50%;
        transform: translate(-50%, 50%);
    }

    .share-dialog {
        display: block;
        width: 99%;
        max-width: 500px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, .15);
        border: 1px solid #ddd;
        padding: 0px;
        border-radius: 15px;
        background-color: #fff;
        z-index: 2;
    }

    .a-open {
        animation-name: block1;
        animation-duration: 1s;
        animation-fill-mode: forwards;
    }

    .a-close {
        animation-name: block2;
        animation-duration: 1s;
        animation-fill-mode: forwards;
    }

    .m-open {
        display: block;
        z-index: 99999;
    }

    @keyframes block1 {
        0% {
            bottom: -500px;
        }

        100% {
            bottom: 50%;
        }
    }

    @keyframes block2 {
        0% {
            bottom: 50%;
        }

        100% {
            bottom: 500px;
        }
    }

    header {
        display: flex;
        /* justify-content: space-between; */
        margin: auto;
        margin: 20px 0 10px 0;
    }


    .close-button {
        background-color: transparent;
        border: none;
        padding: 0;
        margin: auto;
    }

    .close-button svg {
        margin-right: 0;
    }

    .link {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
        font-size: 16px;
    }

    .link_copy {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    .pen-url {
        margin-right: 15px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .popup_social-share-block {
        margin-bottom: 20px;
        padding: 20px;
        border-top: 2px solid #e6eaf5;
        text-align: center;
    }

    ul.social-share-link.pop2 {
        text-align: center;

    }

    ul.social-share-link.pop2 li {
        float: unset;
        text-align: center;
        margin-right: 10px;
        display: inline-block;
    }

    .sh_w {
        font-size: 16px;
        color: #666;
        margin: 1px auto;
    }
</style>
<script type="text/javascript">
    $('.overlay').show();
    var crmid = '<?php echo $crmid; ?>'
    var userid = '<?php echo $userid; ?>'

    $(function() {

        $(document).on('click', '#copy-btn', function() {

            var value = `<?php echo $url; ?>`;
            // console.log(value)
            copytext(value);

        })

    })

    function copytext(text) {
        var textField = document.createElement('textarea');
        textField.innerText = text;
        document.body.appendChild(textField);
        textField.select();
        textField.focus(); //SET FOCUS on the TEXTFIELD
        document.execCommand('copy');
        textField.remove();

        Swal.fire({
            title: '',
            text: 'คัดลอกลิงค์สำเร็จ',
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
        })
    }
</script>