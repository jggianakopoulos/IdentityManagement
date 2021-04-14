package com.group.idm2.Tasks;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.widget.Toast;

import com.group.idm2.Activities.FaceActivity;
import com.group.idm2.Activities.HomeActivity;

import org.json.JSONObject;

import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import okhttp3.Call;
import okhttp3.MultipartBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

import static android.content.Context.MODE_PRIVATE;

public class AbstractTask extends AsyncTask<String, Void, String> {
    public Context context;
    public String email, password, actionWord, script, server;
    public SharedPreferences sharedPreferences;
    public boolean goHome;

    public AbstractTask(Context context, String email, String password) {
        this.context = context;
        this.email = email;
        this.password = password;
        this.actionWord = "action";
        this.script = "test";
        this.sharedPreferences = context.getSharedPreferences("preferences",MODE_PRIVATE);
        this.server = "34.69.148.52";
        this.goHome = true;
    }

    public RequestBody getRequestBody() {
         return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .build();
    }

    protected String doInBackground(String... arg0) {

        try{

            String link = "http://" + this.server + "/api/user/" + this.script + ".php";
            OkHttpClient client = new OkHttpClient.Builder()
                    .build();

            RequestBody requestBody = this.getRequestBody();

            Request request = new Request.Builder()
                    .url(link)
                    .post(requestBody)
                    .build();
            Call call = client.newCall(request);
            Response response = call.execute();
            return response.body().string();
        } catch(Exception e){
            System.out.println(e.getMessage());
            return "0";
        }
    }

    public String getSHA(String input) {

        try {
            MessageDigest md = MessageDigest.getInstance("SHA-256");
            byte[] messageDigest = md.digest(input.getBytes());
            BigInteger no = new BigInteger(1, messageDigest);
            String hashtext = no.toString(16);

            while (hashtext.length() < 32) {
                hashtext = "0" + hashtext;
            }

            return hashtext.toUpperCase();
        } catch (NoSuchAlgorithmException e) {
            System.out.println("Exception thrown"
            + " for incorrect algorithm: " + e);

            return null;
        }
    }

    protected void onPostExecute(String result) {
        System.out.println(result);
        try {
            JSONObject json = new JSONObject(result);
            if (Integer.parseInt(json.getString("user_id")) > 0) {
                //Go to logged in
                System.out.println("Successful action.");
                SharedPreferences.Editor editor = this.sharedPreferences.edit();
                editor.putString("first_name", json.getString("first_name"));
                editor.putString("last_name", json.getString("last_name"));
                editor.putString("email", json.getString("email"));
                editor.putString("password", json.getString("password"));
                editor.putString("use_password", json.getString("use_password"));
                editor.putString("use_face", json.getString("use_face"));
                editor.putString("use_code", json.getString("use_code"));
                editor.apply();

                if (this.goHome) {
                    Intent send = new Intent(this.context, HomeActivity.class);
                    context.startActivity(send);
                }
            } else {
                System.out.println(json.toString());
                Toast.makeText(this.context,"An error occurred with your " + this.actionWord,Toast.LENGTH_SHORT).show();
                if (!this.goHome) {
                    Intent send = new Intent(this.context, FaceActivity.class);
                    context.startActivity(send);

                }
            }
        } catch (Exception e) {
            System.out.println(e.getMessage());
            Toast.makeText(this.context,"An error occurred with your " + this.actionWord,Toast.LENGTH_SHORT).show();

            if (!this.goHome) {
                Intent send = new Intent(this.context, FaceActivity.class);
                context.startActivity(send);

            }
        }
    }
}
