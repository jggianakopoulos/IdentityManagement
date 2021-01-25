package com.group.idm2;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
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

import java.util.ArrayList;
import java.util.List;

public class LoginTask extends AsyncTask<String, Void, String> {
    private Context context;
    private String email, type, actionWord, name, password, confirm_password;

    public LoginTask(Context context, String type, String email, String password, String confirm_password, String name) {
        this.context = context;
        this.email = email;
        this.password = password;
        this.confirm_password = confirm_password;
        this.type = type;
        this.actionWord = (type == "register") ? "registration" : type;
        this.name = name;
    }

    protected String doInBackground(String... arg0) {

        try{
            String link="http://10.0.2.2/identitymanagement/" + this.type + ".php";
            String data  = URLEncoder.encode("email", "UTF-8") + "=" +
                    URLEncoder.encode(this.email, "UTF-8");
            data += "&" + URLEncoder.encode("password", "UTF-8") + "=" +
                    URLEncoder.encode(this.password, "UTF-8");

            if (type == "register") {
                data += "&" + URLEncoder.encode("confirm_password", "UTF-8") + "=" +
                        URLEncoder.encode(this.name, "UTF-8");
                data += "&" + URLEncoder.encode("name", "UTF-8") + "=" +
                        URLEncoder.encode(this.confirm_password, "UTF-8");
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
            return "0";
        }
    }

    protected void onPostExecute(String result) {
        try {
            if (Integer.parseInt(result) > 0) {
                //Go to logged in
                System.out.println("Successful login");
                Intent send = new Intent(this.context, HomeActivity.class);
                context.startActivity(send);
            } else {
                System.out.println(result);
                Toast.makeText(this.context,"An error occurred with your " + this.actionWord,Toast.LENGTH_SHORT).show();
            }
        } catch (Exception e) {
            System.out.println(result);
            Toast.makeText(this.context,"An error occurred with your " + this.actionWord,Toast.LENGTH_SHORT).show();
        }
    }
}
