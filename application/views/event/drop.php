<style>
.success {
 background:url(https://secure.static.tumblr.com/29b14d3af72c26fb0c5c16641d8c5883/alwrjtb/hy6nbidvo/tumblr_static_tumblr_static_8t5iw53wqp8owk44g0c48ko40_640.gif);
  color:#FFF;
  position: relative;
  left: 15%;
  height:263px;
  width:455px;
  top:72px;
  border:2px solid pink;
  text-align:center;
  text-shadow: 0px 0px 2px #000;
}
</style>
<div class="ta-left">
    <div class="success">
            <h3><?=($validtoken ? 'You pick up the leaf to find a neat prize underneath:' : 'Sorry, invalid token!')?></h3>
        <?=($validtoken ? "<h1><img style= 'width:7%;height:7%' src='/images/icons/flowerfall2016.png'></h1>"."<h3>".$prize." Flower"."</h3>" : "") ?>
            <?=($validtoken ? 'Keep an eye out for more or earn them quicker by posting!' :  'This token is either expired or has already been used. You can find more around the site!') ?>
    </div>

</div>
