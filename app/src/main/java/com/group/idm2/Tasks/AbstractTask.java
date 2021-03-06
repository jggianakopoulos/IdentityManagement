package com.group.idm2.Tasks;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.widget.Toast;

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
    public String email, password, actionWord, script;
    public SharedPreferences sharedPreferences;

    public AbstractTask(Context context, String email, String password) {
        this.context = context;
        this.email = email;
        this.password = password;
        this.actionWord = "action";
        this.script = "test";
        this.sharedPreferences = context.getSharedPreferences("preferences",MODE_PRIVATE);
    }

    public RequestBody getRequestBody() {
         return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .build();
    }

    protected String doInBackground(String... arg0) {

        try{

            String link = "http://34.86.252.125/" + this.script + ".php";
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

            return hashtext;
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
            JSONObject user = json.getJSONObject("user");
            if (Integer.parseInt(user.getString("UserID")) > 0) {
                //Go to logged in
                System.out.println("Successful action.");
                SharedPreferences.Editor editor = this.sharedPreferences.edit();
                editor.putString("first_name", user.getString("FirstName"));
                editor.putString("last_name", user.getString("LastName"));
                editor.putString("email", user.getString("Email"));
                //editor.putString("phone_number", user.getString("phone_number"));
                editor.apply();

                Intent send = new Intent(this.context, HomeActivity.class);
                context.startActivity(send);
            } else {
                System.out.println(json.toString());
                Toast.makeText(this.context,"An error occurred with your " + this.actionWord,Toast.LENGTH_SHORT).show();
            }
        } catch (Exception e) {
            System.out.println(e.getMessage());
            Toast.makeText(this.context,"An error occurred with your " + this.actionWord,Toast.LENGTH_SHORT).show();
        }
    }
}
