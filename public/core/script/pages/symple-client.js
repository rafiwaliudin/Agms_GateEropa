
function prepareSympleClient(client_options) {
    var token = client_options.peer.user.substr(6);
    
    if (token.length <= 0) return;

    var client, player, remotePeer, initialized = false;

    // WebRTC config
    // This is where you would add TURN servers for use in production
    WEBRTC_CONFIG = {
        'iceServers': [
            // {'url': 'stun:stun.l.google.com:19302'},
            {
                'url': 'turn:numb.viagenie.ca:3478',
                'username': 'yuri@alfabeta.co.id',
                'credential': 'alfabeta123'
            }
        ]
    }

    // // Intercept Symple log messages
    // Symple.log = function () {
    //     var args = Array.prototype.slice.call(arguments);
    //     $('#webrtc-logs').append(JSON.stringify(args)).append('\n');
    //     console.log.apply(console, arguments);
    // }
    
    //
    // Initialize the Symple WebRTC player

    $(".video-player").hide(); // Dont show local media
    player = new Symple.Player({
        element: '#webrtc-video .video-player',
        // element: '#camera-preview',
        engine: 'WebRTC',
        initiator: true,
        rtcConfig: WEBRTC_CONFIG,
        iceMediaConstraints: {
            'mandatory': {
                'OfferToReceiveAudio': false,
                'OfferToReceiveVideo': true
            }
        },
        onStateChange: function(player, state) {
            player.displayStatus(state);
        }
    });
    
    //
    // Initialize the Symple client
    function startPlaybackAndRecording() {
        // player.setup();
        player.play(); //{ localMedia: true, disableAudio: false, disableVideo: false }
        player.engine.sendLocalSDP = function(desc) {
            console.log('Send offer:', JSON.stringify(desc));
            client.send({
                to: remotePeer,
                type: 'message',
                offer: desc
            });
        };

        player.engine.sendLocalCandidate = function(cand) {
            client.send({
                to: remotePeer,
                type: 'message',
                candidate: cand
            });
        }
    }

    //
    // Initialize the Symple client

    client = new Symple.Client(client_options);

    client.on('announce', function(peer) {
        console.log('Authentication success:', peer);
    });

    client.on('addPeer', function(peer) {
        console.log('Adding peer:', peer);

        if (peer.user == 'abdetection'+token &&
            !initialized) {
            initialized = true;
            remotePeer = peer; //m.from;
            startPlaybackAndRecording();
        }
    });

    client.on('removePeer', function(peer) {
        console.log('Removing peer:', peer);
    });

    client.on('message', function(m) {
        // console.log('Recv message:', m)
        if (remotePeer && remotePeer.id != m.from.id) {
            console.log('Dropping message from unknown peer', m);
            return;
        }
        if (m.offer) {
            alert('Unexpected offer for one-way streaming');
        }
        else if (m.answer) {
            console.log('Reieve answer:', JSON.stringify(m.answer));
            player.engine.recvRemoteSDP(m.answer);
        }
        else if (m.candidate) {
            player.engine.recvRemoteCandidate(m.candidate);
        }
    });

    client.on('disconnect', function() {
        console.log('Disconnected from server')
    });

    client.on('error', function(error, message) {
        console.log('Peer error:', error, message)
    });

    client.connect();    
}

