<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>P2P云播放</title>
    <script src="//cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/dplayer@1.26.0"></script>
    <script src="https://m3u8.tsite.top/cdn/p2p.min.js"></script>
    <style type="text/css">
    body, html {
        width: 100%;
        height: 100%;
        background: #000;
        padding: 0;
        margin: 0;
        overflow-x: hidden;
        overflow-y: hidden
    }

    * {
        margin: 0;
        border: 0;
        padding: 0;
        text-decoration: none
    }

    #stats {
        position: fixed;
        top: 5px;
        left: 10px;
        font-size: 12px;
        color: #fdfdfd;
        z-index: 2147483647;
        text-shadow: 1px 1px 1px #000, 1px 1px 1px #000
    }

    #dplayer {
        position: inherit
    }
</style>
</head>
<body>

<div id="dplayer"></div>
<div id="stats"></div>
<script type="text/javascript">
    var hls;
    var dp = new DPlayer({
        container: document.getElementById('dplayer'),
        screenshot: true,
        video: {
            // url: 'https://c2.monidai.com/20220131/oKKaA2uZ/index.m3u8',
            url : '<?php echo($_REQUEST['url']);?>',
            type: 'customHls',
            customType: {
                'customHls': function (video, player) {
                    hls = new Hls({
                        // Other hlsjsConfig options provided by hls.js
                        p2pConfig: {
                            announce: "https://raycdn.tsite.top",
                            wsSignalerAddr: 'wss://signal.tsite.top/',
                        }
                    });
                    hls.loadSource(video.src);
                    hls.attachMedia(video);
                    _peerNum = 0;
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
        },
    });
function updateStats() {
    var text = 'P2P正在为您加速' + (_totalP2PDownloaded/1024).toFixed(2)
        + 'MB 已分享' + (_totalP2PUploaded/1024).toFixed(2) + 'MB' + ' 连接节点' + _peerNum + '个';
    document.getElementById('stats').innerText = text
}
</script>

</body>
</html>