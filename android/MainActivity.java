package com.lumhue;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;

import com.estimote.sdk.BeaconManager;
import com.estimote.sdk.EstimoteSDK;
import com.estimote.sdk.Nearable;
import com.estimote.sdk.Utils;
import com.neovisionaries.ws.client.WebSocket;
import com.neovisionaries.ws.client.WebSocketAdapter;
import com.neovisionaries.ws.client.WebSocketFactory;

import java.util.List;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    private BeaconManager beaconManager;
    private String scanId;
    private WebSocket ws;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        EstimoteSDK.initialize(this, Constants.AppId, Constants.AppToken);
        beaconManager = new BeaconManager(this);
        beaconManager.setNearableListener(new BeaconManager.NearableListener() {
            @Override
            public void onNearablesDiscovered(List<Nearable> nearables) {
                for (Nearable n : nearables) {
                    Utils.Proximity distance = Utils.computeProximity(n);
                    // ws.sendText(distance.toString());
                }
            }
        });

        beaconManager.connect(new BeaconManager.ServiceReadyCallback() {
            @Override
            public void onServiceReady() {
                scanId = beaconManager.startNearableDiscovery();
            }
        });

        try {
            ws = new WebSocketFactory().createSocket(Constants.WS_ADDR, 5000);
            ws.addListener(new WebSocketAdapter() {
                @Override
                public void onConnected(WebSocket websocket, Map<String, List<String>> headers) throws Exception {
                    String str = "{ \"protocol\" : \"chat\", "
                                  + "\"type\" : \"auth\", "
                                  + "\"data\" : { \"name\" : \"root\" }, "
                                  + "\"token\" : \"" + Constants.TOKEN + "\""
                               + "}";
                    websocket.sendText(str);
                }

            });
            ws.connectAsynchronously();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Override
    protected void onStop() {
        super.onStop();
        beaconManager.stopNearableDiscovery(scanId);
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        beaconManager.disconnect();
    }
}
