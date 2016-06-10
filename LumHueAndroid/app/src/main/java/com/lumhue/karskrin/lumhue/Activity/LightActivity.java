package com.lumhue.karskrin.lumhue.Activity;

import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.GradientDrawable;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Switch;
import android.widget.TextView;

import com.google.gson.Gson;
import com.lumhue.karskrin.lumhue.API.Lumhueapi;
import com.lumhue.karskrin.lumhue.MainActivity;
import com.lumhue.karskrin.lumhue.R;
import com.lumhue.karskrin.lumhue.model.Lumhuemodel;
import com.lumhue.karskrin.lumhue.model.Rgb;
import com.pes.androidmaterialcolorpickerdialog.ColorPicker;

import butterknife.ButterKnife;
import butterknife.InjectView;
import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class LightActivity extends BaseActivity {
    @InjectView(R.id.SaveLight)
    Button saveLight;
    @InjectView(R.id.txtLight)
    TextView viewName;
    @InjectView(R.id.colorCircle)
    ImageView colorCircle;
    @InjectView(R.id.switchOn)
    Switch switchOn;
    private Lumhuemodel model;
    private String API;
    private int position;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LayoutInflater inflater = (LayoutInflater) this
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.activity_light, null, false);
        mDrawerLayout.addView(contentView, 0);
        API = getResources().getString(R.string.api);
        ButterKnife.inject(this);

        // Retrieve information passed from previous activity
        Bundle extras = getIntent().getExtras();
        if (extras != null) {
            position = extras.getInt("position");
            String jsonMyObject = extras.getString("model");
            model = new Gson().fromJson(jsonMyObject, Lumhuemodel.class);
        }

        // Set name according to the name of the lamp
        viewName.setText(model.name);

        // Set color of the circle according to the color of the lamp
        final Rgb rgb = model.rgb;
        int color = Color.rgb(rgb.r, rgb.g, rgb.b);
        if (!model.state.on)
            color = 0;

        GradientDrawable gd = (GradientDrawable) colorCircle.getDrawable();
        gd.setColor(color);
        gd.setStroke(1, Color.WHITE);
        // Set initial position of switch on state of the light
        switchOn.setChecked(model.state.on);

        saveLight.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                post(model, switchOn.isChecked());
            }
        });

        // Color picker
        final ColorPicker cp = new ColorPicker(this, rgb.r, rgb.g, rgb.b);
        colorCircle.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                  /* Show color picker dialog */
                cp.show();

    /* On Click listener for the dialog, when the user select the color */
                Button okColor = (Button) cp.findViewById(R.id.okColorButton);
                okColor.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {

                /* You can get single channel (value 0-255) */
                        model.rgb.r = cp.getRed();
                        model.rgb.g = cp.getGreen();
                        model.rgb.b = cp.getBlue();
                        final Rgb rgb = model.rgb;
                        int color = Color.rgb(rgb.r, rgb.g, rgb.b);
                        if (!model.state.on || !model.state.reachable)
                            color = 0;
                        colorCircle.setColorFilter(color);
                        cp.dismiss();
                    }
                });
            }
        });
    }

    private void post(final Lumhuemodel model, Boolean reachable) {
        RestAdapter restAdapter = new RestAdapter.Builder().setLogLevel(RestAdapter.LogLevel.FULL).setEndpoint(API).build();
        final Lumhueapi lumhueapi = restAdapter.create(Lumhueapi.class);
        Log.v("LIGHTS activity reach: ", reachable.toString());
        lumhueapi.postLights(MainActivity.token, "rgba(" + model.rgb.r + ", " + model.rgb.g + ", " + model.rgb.b + ")", position + 1, reachable.toString(), new Callback<Lumhuemodel>() {
            @Override
            public void success(Lumhuemodel lumhuemodel, Response response) {
                Log.v("LIGHTS activity", "It works");
            }

            @Override
            public void failure(RetrofitError error) {
                String tv = error.getMessage();
                Log.v("LIGHTS activity", tv);
            }
        });

    }
}
