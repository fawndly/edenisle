 <!-- Crysandrea engine by Tyler Diaz (https://github.com/Pixeltweak/Crysandrea) --><?php $this->load->view('layout/feature_navigation', array('routes' => $routes, 'active_url' => $active_url, 'core' => 'donate')); ?>
<?php // $this->load->view('partials/notices/notice', array('data' => 'The donation system works just fine. It\'s not at its prettiest, but it works just as stable as our old one. Some design elements are temporary.')) ?>

<style type="text/css">
.item_name{font-size:15px;color:#111;font-family:Arial}
.donation_container{overflow:hidden;border-bottom:1px solid #ccc;margin-bottom:10px;margin-top:5px}
.donation_container:last-child{border:0}
.donation_element{width: 75%;
margin: 0px auto 0px auto;}
</style>

<div style="position:relative; margin-left: 5px; width:300px;">
    <img src="/images/tyi_images/thankyougifts.png" alt="" style="margin: 8px 0;">
</div>

<?php
    if ($this->input->get('success')) {
        $this->load->view('partials/notices/success', array(
            'header' => 'Your item has been purchased!',
            'data'   => 'Your item has been gently placed inside your inventory, thank you for purchasing a donation item!'
        ));
    }

    $logged_in_user_id = $this->session->userdata('user_id');
?>

<div style="overflow:hidden">
    <div class="grid_3">
        <?php if($logged_in_user_id): ?>
            <h3 class="donate_small_header">You currently own <span><?= number_format($user['user_'.$this->currency_name]) ?> Sapphires</span></h3>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="form-inline">
              <input type="hidden" name="cmd" value="_s-xclick">
              <input type="hidden" name="hosted_button_id" value="U9EXSDRBS86KJ">
              <input type="hidden" name="on0" value="Sapphires">
              <div class="form-group" style="text-align: center">

                <input type="hidden" name="on0" value="Sapphires">Sapphires<br />
                <select name="os0">
                      <option value="25 Sapphires">25 Sapphires $1.00 USD</option>
                      <option value="50 Sapphires">50 Sapphires $2.00 USD</option>
                      <option value="135 Sapphires">135 Sapphires $5.00 USD</option>
                      <option value="275 Sapphires">275 Sapphires $10.00 USD</option>
                      <option value="425 Sapphires">425 Sapphires $15.00 USD</option>
                      <option value="575 Sapphires">575 Sapphires $20.00 USD</option>
                      <option value="900 Sapphires">900 Sapphires $30.00 USD</option>
                      <option value="1,600 Sapphires">1,600 Sapphires $50.00 USD</option>
                      <option value="2,575 Sapphires">2,575 Sapphires $75.00 USD</option>
                      <option value="3,550 Sapphires">3,550 Sapphires $100.00 USD</option>
                </select>
              </table>
              </div>
              <input type="hidden" name="currency_code" value="USD">
              <input type="hidden" name="custom" value="<?php echo $logged_in_user_id ?>">
              <p style="text-align:center">
                <input type="submit" class="fancyButton" value="Donate" name="submit">
              </p>
              <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        <?php else : ?>
            <h3 class="donate_small_header">Hello! Why don't you login to check how many Sapphires you have?<br /><br />Not a member yet? Why not join us~? You can register clicking on that fancy button over there.</h3>
        <?php endif ?>
    </div>
    <div class="grid_3 style="margin-top:40px">
        <?php foreach ($items as $item): ?>
        <div class="donation_container">
            <p style="text-align:center"><strong class="item_name"><?= $item['name'] ?></strong></p>

            <?php if($logged_in_user_id): ?>
            <div class="donation_element">
            <p style="text-align:center">
            <img id="<?= $item['item_id']?>" src="/avatar/preview_item/<?= $item['item_id'] ?>" alt="" class="tyi_preview_avatar img-responsive center-block"/>
            </p>
            </div>
            <?php endif ?>
            <div class="donation_element">
            <?php foreach ($item['children'] as $id=>$children): 
                if($id%5==0) echo "<div class='row'>";?>
            <a href="#" data-item-id="<?= $children['item_id'] ?>" parent="<?= $item['item_id']?>"class="thumbnail_toggle">
                <img src="/images/items/<?= $children['thumb'] ?>" alt="" />
            </a>
            <?php  if($id%5==4) echo "</div>";
                                endforeach;
                                if((count($item['children'])-1)%5!=4) echo "</div>"; ?>
            <?php if($logged_in_user_id): ?>
            <div style="clear: both"></div>
            <div style="border-top:1px solid #ddd; margin-top: 10px; padding-top:5px; width:100%;">
                <form action="/donate/purchase_item" method="POST" style="text-align: center;">
                <span>Price: <?= ($item['type'] == 'tyi' ? $prices['tyi'] : 15) ?> <?= ucfirst($this->currency_name) ?></span> &bull;
                Total:
                <select name="total" style="width:60px; height:auto; line-height:auto; padding:0; margin-bottom:0;">
                    <option value="1">x1</option>
                    <option value="2">x2</option>
                    <option value="3">x3</option>
                    <option value="5">x5</option>
                    <option value="10">x10</option>
                </select>
                <br />
                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>" />
                <button class="main_button" type="submit">Purchase item</button>
                </form>
            </div>
            <?php endif ?>
        </div>
        <?php endforeach ?>
    </div>
</div>


<style type="text/css">
    h3.donate_small_header{font-size:14px;color:#444;text-align:center;margin-top:25px}
    .fancyButton {
        -moz-box-shadow:inset 0px 1px 0px 0px #dcecfb;
        -webkit-box-shadow:inset 0px 1px 0px 0px #dcecfb;
        box-shadow:inset 0px 1px 0px 0px #dcecfb;
        background:linear-gradient(to bottom, #b3d5e2 5%, #3176c4 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#b3d5e2', endColorstr='#3176c4',GradientType=0);
        background-color:#b3d5e2;
        -moz-border-radius:6px;
        -webkit-border-radius:6px;
        border-radius:6px;
        border:1px solid #84bbf3;
        display:inline-block;
        cursor:pointer;
        color:#ffffff !important;
        font-family:arial;
        font-size:15px;
        font-weight:bold;
        padding:8px 24px;
        text-decoration:none;
        text-shadow:0px 1px 0px #528ecc;
        margin-top: 4px;
    }
    .fancyButton:hover {
        background:linear-gradient(to bottom, #3176c4 5%, #b3d5e2 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3176c4', endColorstr='#b3d5e2',GradientType=0);
        background-color:#3176c4;
        text-decoration:none
    }
    .fancyButton:active {
        position:relative;
        top:1px;
    }

    .don_button{
        display:block;
        background: #3498db;
        background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
        background-image: -moz-linear-gradient(top, #3498db, #2980b9);
        background-image: -ms-linear-gradient(top, #3498db, #2980b9);
        background-image: -o-linear-gradient(top, #3498db, #2980b9);
        background-image: linear-gradient(to bottom, #3498db, #2980b9);
        -webkit-border-radius: 8;
        -moz-border-radius: 8;
        border-radius: 8px;
        -webkit-box-shadow: 0px 1px 2px #666666;
        -moz-box-shadow: 0px 1px 2px #666666;
        box-shadow: 0px 1px 2px #666666;
        font-family: Arial;
        color: #ffffff !important;
        font-size: 18px;
        padding: 8px 20px 8px 20px;
        border: solid #2c74a1 1px;
        text-decoration: none;
        text-align: center;
        width: 60px !important;
    }
    
    .don_button:hover{
        background:#2A8BC1;
        text-decoration:none
    }
    
    .don_button:active{
        color:#ddd;
        background:#13618D;
        text-decoration:none;
        box-shadow:inset 0 2px 2px rgba(0,0,0,.1)
    }

    .disabled{
        pointer-events:none;
        cursor:default;
        -webkit-filter: grayscale(100%);
        }

</style>
