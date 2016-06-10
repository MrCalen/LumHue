package com.lumhue.karskrin.lumhue.View;

import android.app.Fragment;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;

import com.google.gson.Gson;
import com.lumhue.karskrin.lumhue.API.Lumhueapi;
import com.lumhue.karskrin.lumhue.Activity.LightActivity;
import com.lumhue.karskrin.lumhue.Adapter.LumhuemodelAdapter;
import com.lumhue.karskrin.lumhue.MainActivity;
import com.lumhue.karskrin.lumhue.R;
import com.lumhue.karskrin.lumhue.model.Lumhuemodel;

import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class LightsFragment extends Fragment {
    Button click;
    ProgressBar pbar;
    private String API;
    private LinearLayout mLayout;
    private ListView mListView;
    private ArrayList<Lumhuemodel> adapter;
    private LumhuemodelAdapter adapterr;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        API = getResources().getString(R.string.api);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        return inflater.inflate(R.layout.lights, container, false);
    }

    @Override
    public void onStart() {
        super.onStart();
        // Inflate the layout for this fragment
        mLayout = (LinearLayout) getView().findViewById(R.id.lightsLayout);
        click = (Button) getView().findViewById(R.id.button);
        pbar = (ProgressBar) getView().findViewById(R.id.pb);
        mListView = (ListView) getView().findViewById(R.id.listView);

        adapter = new ArrayList<>();
        adapterr = new LumhuemodelAdapter(this, R.layout.listview_light_row, adapter);
        mListView.setAdapter(adapterr);
        pbar.setVisibility(View.INVISIBLE);

        mListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapter, View view, int position, long arg) {
                Intent intent = new Intent(getActivity(), LightActivity.class);
                intent.putExtra("position", position);
                intent.putExtra("model", new Gson().toJson(adapter.getItemAtPosition(position)));
                startActivity(intent);
            }
        });

        get(MainActivity.token);
    }

    public void get(final String token) {
        RestAdapter restAdapter = new RestAdapter.Builder().setLogLevel(RestAdapter.LogLevel.FULL).setEndpoint(API).build();
        final Lumhueapi lumhueapi = restAdapter.create(Lumhueapi.class);
        pbar.setVisibility(View.VISIBLE);
        adapterr.clear();
        lumhueapi.getLights(token, new Callback<List<Lumhuemodel>>() {
            @Override
            public void success(List<Lumhuemodel> lumhuemodel, Response response) {
                for (Lumhuemodel entry : lumhuemodel) {
                    adapter.add(entry);
                    adapterr.notifyDataSetChanged();
                }
                pbar.setVisibility(View.INVISIBLE);
            }

            @Override
            public void failure(RetrofitError error) {
                String tv = error.getMessage();
                Log.v("LIGHTS fragment", tv);
                pbar.setVisibility(View.INVISIBLE);
            }
        });
    }
}
