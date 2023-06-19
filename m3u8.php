<?php 
/**
 * @author CJ22
 * @copyright 2018
 * @version    1.0
 *
 * for ray-p2p system
 *
 */
?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" /> 
  <meta name="referrer" content="never" /> 
  <title>云解析</title> 
  <style type="text/css">body,html,.dplayer{padding:0;margin:0;width:100%;height:100%;background-color:#000}a{text-decoration:none}
  /*#stats{position:fixed;top:5px;left:10px;font-size:9px;color:#fdfdfd;z-index:20719029;text-shadow:1px 1px 1px #000, 1px 1px 1px #000} */
</style> 
 </head> 
 <body> 
 <link rel="stylesheet" href="./DPlayer.min.css">
<div id="dplayer"></div>
<div id="stats"></div>
<script src="https://m3u8.bapy.top/p2p.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/dplayer@1.26.0"></script>
<script>
var _peerId = '', _peerNum = 0, _totalP2PDownloaded = 0, _totalP2PUploaded = 0;	
    function updateStats() {
        var text = 'P2P正在为您加速' + (_totalP2PDownloaded/1024).toFixed(2)
            + 'MB 已分享' + (_totalP2PUploaded/1024).toFixed(2) + 'MB' + ' 连接节点' + _peerNum + '个';
        document.getElementById('stats').innerText = text;
    }
    var hlsjsConfig = {
        debug: false,
        maxBufferHole: 5,
        p2pConfig: {
            logLevel: 'warn',
            announce: "https://single.bapy.top",
            wsSignalerAddr: 'wss://single.bapy.top/ws',
            live: true,
        }
        
    };
    var hls;
    var dp = new DPlayer({
        autoplay:true,
        container: document.getElementById('dplayer'),
        loop:true,
        screenshot:true,
        hotkey:true,
        video: {
            url: '<?php echo($_REQUEST['url']);?>',
            //pic: 'logo.png',
            type: 'customHls',
            customType: {
                'customHls': function (video, player) {
                    var isMobile = navigator.userAgent.match(/iPad|iPhone|Linux|Android|iPod/i) != null;
                    if (0) {
                        var html = '<video src="'+video.src+'" controls="controls" autoplay="autoplay" width="100%" height="100%"></video>';
                        document.getElementById('dplayer').innerHTML = html;
                    }else{
                        hls = new Hls(hlsjsConfig);
                        hls.loadSource(video.src);
                        hls.attachMedia(video);
                        hls.engine.on('stats', function (stats) {
                            _totalP2PDownloaded = stats.totalP2PDownloaded;
                            _totalP2PUploaded = stats.totalP2PUploaded;
                            updateStats();
                        }).on('peerId', function (peerId) {
                            _peerId = peerId;
                        }).on('peers', function (peers) {
                            _peerNum = peers.length;
                            updateStats();
                        });
                    }
                }
            }
        }
    });
    
    dp.play();


</script>
</body>
</html>