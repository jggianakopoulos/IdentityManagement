package com.group.idm2;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.math.BigInteger;
import java.net.URI;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import java.security.NoSuchAlgorithmException;
import java.util.ArrayList;
import java.util.List;
import java.security.MessageDigest;

import static android.content.Context.MODE_PRIVATE;
import static android.os.Build.VERSION_CODES.M;
import android.content.SharedPreferences;



public class LoginTask extends AsyncTask<String, Void, String> {
    private Context context;
    private String email, type, actionWord, first_name, last_name, password, confirm_password, phone_number, old_email, new_password;
    private SharedPreferences sharedPreferences;


    public LoginTask(Context context, String type, String email, String password, String confirm_password, String first_name, String last_name, String phone_number, String new_password) {
        this.context = context;
        this.email = email;
        this.password = password;
        this.confirm_password = confirm_password;
        this.type = type;
        this.phone_number = phone_number;
        this.actionWord = (type == "register") ? "registration" : (type == "profileupdate") ? "profile update" : (type == "passwordchange") ? "password update" : type;
        this.first_name = first_name;
        this.last_name = last_name;
        this.new_password = new_password;
        this.sharedPreferences = context.getSharedPreferences("preferences",MODE_PRIVATE);
    }

    protected String doInBackground(String... arg0) {

        try{
            String link="http://10.0.2.2/identitymanagement/" + this.type + ".php";
            String data  = URLEncoder.encode("email", "UTF-8") + "=" +
                    URLEncoder.encode(this.email, "UTF-8");
            data += "&" + URLEncoder.encode("password", "UTF-8") + "=" +
                    URLEncoder.encode(getSHA(this.password), "UTF-8");

            if (type == "register" || type == "profileupdate") {
                data += "&" + URLEncoder.encode("first_name", "UTF-8") + "=" +
                        URLEncoder.encode(this.first_name, "UTF-8");
                data += "&" + URLEncoder.encode("last_name", "UTF-8") + "=" +
                        URLEncoder.encode(this.last_name, "UTF-8");
                data += "&" + URLEncoder.encode("phone_number", "UTF-8") + "=" +
                        URLEncoder.encode(this.phone_number, "UTF-8");

                if (type == "register") {
                    data += "&" + URLEncoder.encode("confirm_password", "UTF-8") + "=" +
                            URLEncoder.encode(getSHA(this.confirm_password), "UTF-8");
                } else {
                    SharedPreferences sharedPreferences = context.getSharedPreferences("preferences",MODE_PRIVATE);
                    old_email = sharedPreferences.getString("email", "");
                    data += "&" + URLEncoder.encode("old_email", "UTF-8") + "=" +
                            URLEncoder.encode(old_email, "UTF-8");
                }
            } else if (type == "passwordchange") {
                data += "&" + URLEncoder.encode("new_password", "UTF-8") + "=" +
                        URLEncoder.encode(getSHA(this.new_password), "UTF-8");
                data += "&" + URLEncoder.encode("confirm_password", "UTF-8") + "=" +
                        URLEncoder.encode(getSHA(this.confirm_password), "UTF-8");
            }

            URL url = new URL(link);
            URLConnection conn = url.openConnection();

            conn.setDoOutput(true);
            OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());

            wr.write( data );
            wr.flush();

            BufferedReader reader = new BufferedReader(new
                    InputStreamReader(conn.getInputStream()));

            StringBuilder sb = new StringBuilder();
            String line = null;

            // Read Server Response
            while((line = reader.readLine()) != null) {
                sb.append(line);
                break;
            }

            return sb.toString();
        } catch(Exception e){
            System.out.println(e.getMessage());
            return "0";
        }
    }

    public static String getSHA(String input)
    {

        try {

            // Static getInstance method is called with hashing SHA
            MessageDigest md = MessageDigest.getInstance("SHA-256");

            // digest() method called
            // to calculate message digest of an input
            // and return array of byte
            byte[] messageDigest = md.digest(input.getBytes());

            // Convert byte array into signum representation
            BigInteger no = new BigInteger(1, messageDigest);

            // Convert message digest into hex value
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
            if (Integer.parseInt(user.getString("user_id")) > 0) {
                //Go to logged in
                System.out.println("Successful login");
                SharedPreferences.Editor editor = this.sharedPreferences.edit();
                editor.putString("first_name", user.getString("first_name"));
                editor.putString("last_name", user.getString("last_name"));
                editor.putString("email", user.getString("email"));
                editor.putString("phone_number", user.getString("phone_number"));
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
