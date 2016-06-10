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
import com.lumhue.karskrin.lumhue.View.AmbiancesFragment;
import com.lumhue.karskrin.lumhue.model.Ambiance;

import java.util.ArrayList;
import java.util.List;

public class AmbianceAdapter extends ArrayAdapter<Ambiance> {
    List<Ambiance> ambiances = null;
    AmbiancesFragment context;
    int layoutResourceId;
    String API = "https://karskrin.mr-calen.eu/api";

    public AmbianceAdapter(AmbiancesFragment context, int resource, List<Ambiance> objects) {
        super(context.getActivity().getBaseContext(), resource, objects);
        this.layoutResourceId = resource;
        this.context = context;
        this.ambiances = objects;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View row = convertView;
        AmbianceHolder holder;
        if (row == null) {
            LayoutInflater inflater = (context.getActivity()).getLayoutInflater();
            row = inflater.inflate(layoutResourceId, parent, false);

            holder = new AmbianceHolder();
            holder.txtview = (TextView) row.findViewById(R.id.txtRow);
            holder.colorCircles.add((ImageView) row.findViewById(R.id.colorCircle1));
            holder.colorCircles.add((ImageView) row.findViewById(R.id.colorCircle2));
            holder.colorCircles.add((ImageView) row.findViewById(R.id.colorCircle3));
            holder.ambiance = ambiances.get(position);

            for (int i = 0; i < holder.colorCircles.size(); i++) {
                int rgb = Color.parseColor(holder.ambiance.lights.get(0).lightscolors.get(i).rgbhex);
                if (!holder.ambiance.lights.get(0).lightscolors.get(i).on)
                    rgb = 0;
                GradientDrawable gd = (GradientDrawable) holder.colorCircles.get(i).getDrawable();
                gd.setColor(rgb);
                gd.setStroke(1, Color.WHITE);
            }

            final AmbianceHolder finalHolder = holder;

            row.setTag(holder);
        } else {
            holder = (AmbianceHolder) row.getTag();
        }

        Ambiance ambiance = ambiances.get(position);
        holder.txtview.setText(ambiance.name);

        return row;
    }

    static class AmbianceHolder {
        TextView txtview;
        List<ImageView> colorCircles = new ArrayList<>();
        ImageView colorCircle1;
        ImageView colorCircle2;
        ImageView colorCircle3;
        Ambiance ambiance;
    }
}
