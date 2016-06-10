package com.lumhue.karskrin.lumhue.Adapter;

import android.graphics.Color;
import android.graphics.drawable.GradientDrawable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.lumhue.karskrin.lumhue.R;
import com.lumhue.karskrin.lumhue.View.LightsFragment;
import com.lumhue.karskrin.lumhue.model.Lumhuemodel;
import com.lumhue.karskrin.lumhue.model.Rgb;

import java.util.List;


public class LumhuemodelAdapter extends ArrayAdapter<Lumhuemodel> {
    List<Lumhuemodel> lights = null;
    LightsFragment context;
    int layoutResourceId;
    String API = "https://karskrin.mr-calen.eu/api";

    public LumhuemodelAdapter(LightsFragment context, int resource, List<Lumhuemodel> objects) {
        super(context.getActivity().getBaseContext(), resource, objects);
        this.layoutResourceId = resource;
        this.context = context;
        this.lights = objects;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View row = convertView;
        LumhuemodelHolder holder;
        if (row == null) {
            LayoutInflater inflater = (context.getActivity()).getLayoutInflater();
            row = inflater.inflate(layoutResourceId, parent, false);

            holder = new LumhuemodelHolder();
            holder.txtview = (TextView) row.findViewById(R.id.txtRow);
            holder.colorCircle = (ImageView) row.findViewById(R.id.colorCircle);

            holder.model = lights.get(position);
            Rgb rgb = holder.model.rgb;
            int color = Color.rgb(rgb.r, rgb.g, rgb.b);

            if (!holder.model.state.reachable)
                color = 0;
            GradientDrawable gd = (GradientDrawable) holder.colorCircle.getDrawable();
            gd.setStroke(1, Color.WHITE);
            gd.setColor(color);
            final LumhuemodelHolder finalHolder = holder;

            row.setTag(holder);
        } else {
            holder = (LumhuemodelHolder) row.getTag();
        }

        Lumhuemodel model = lights.get(position);
        holder.txtview.setText(model.name);

        return row;
    }

    static class LumhuemodelHolder {
        TextView txtview;
        ImageView colorCircle;
        Lumhuemodel model;
    }
}
