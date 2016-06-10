package com.lumhue.karskrin.lumhue.API;

import com.lumhue.karskrin.lumhue.model.AmbianceApplyResponse;
import com.lumhue.karskrin.lumhue.model.AmbianceModel;
import com.lumhue.karskrin.lumhue.model.LoginResponseDTO;
import com.lumhue.karskrin.lumhue.model.Lumhuemodel;

import java.util.HashMap;
import java.util.List;

import retrofit.Callback;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Query;

public interface Lumhueapi {
    @GET("/lights")
        //here is the other url part.best way is to start using /
    void getLights(@Query("access_token") String token, Callback<List<Lumhuemodel>> response);
    //response is the response from the server which is now in the POJO
    @FormUrlEncoded
    @POST("/lights")
    //here is the other url part.best way is to start using /
    void postLights(@Field("access_token") String token, @Field("color") String rgb, @Field("id") Integer id, @Field("on") String on, Callback<Lumhuemodel> response);
    //response is the response from the server which is now in the POJO

    // Function to login
    @FormUrlEncoded
    @POST("/signin")
    void postLogin(@Field("email") String email, @Field("password") String password, Callback<LoginResponseDTO> response);

    // Function to register
    @FormUrlEncoded
    @POST("/signup")
    void postSignup(@Field("name") String name, @Field("email") String email, @Field("password") String password, Callback<LoginResponseDTO> response);

    //Get the ambiances
    @GET("/ambiance")
    void getAmbiances(@Query("access_token") String token, Callback<HashMap<String, AmbianceModel>> response);

    //Apply an ambiance
    @FormUrlEncoded
    @POST("/ambiance/apply")
    void applyAmbiance(@Field("access_token") String token, @Field("ambiance_id") String id, Callback<AmbianceApplyResponse> response);
}