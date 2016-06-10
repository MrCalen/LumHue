package com.lumhue.karskrin.lumhue.model;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Karskrin on 06/06/2016.
 */
public class Ambiance {

    public String name;
    public List<Light> lights = new ArrayList<Light>();
    public String uniq_id;
}
