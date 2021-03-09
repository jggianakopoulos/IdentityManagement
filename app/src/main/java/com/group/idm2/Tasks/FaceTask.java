package com.group.idm2.Tasks;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.os.AsyncTask;
import android.util.Base64;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.math.BigInteger;
import java.net.HttpURLConnection;
import java.net.URI;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;

import org.json.JSONObject;

import java.security.NoSuchAlgorithmException;
import java.util.ArrayList;
import java.util.List;
import java.security.MessageDigest;

import static android.content.Context.MODE_PRIVATE;
import static android.os.Build.VERSION_CODES.M;
import android.content.SharedPreferences;


import okhttp3.Call;
import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class FaceTask extends AsyncTask<String, Void, String> {
    private Context context;
    private Bitmap image;
    private SharedPreferences sharedPreferences;


    public FaceTask(Context context, Bitmap image) {
        this.image = image;
        this.context = context;
        this.sharedPreferences = context.getSharedPreferences("preferences",MODE_PRIVATE);
    }

    protected String doInBackground(String... arg0) {

        try {
            String link = "http://10.0.2.2/identitymanagement/updateface.php";
            OkHttpClient client = new OkHttpClient.Builder()
                    .build();

            ByteArrayOutputStream out = new ByteArrayOutputStream();
            image.compress(Bitmap.CompressFormat.JPEG, 100, out);

//            String dir= context.getFilesDir().toString();
//            System.out.println(dir);


     //               .addFormDataPart("m", "file.txt",
      //              RequestBody.create(MediaType.parse("application/octet-stream"),
      //                      dir + "/test.jpeg"))

//            OutputStream outstream = new FileOutputStream(Environment.getExternalStorageDirectory().getAbsolutePath()+"/test.jpeg");

            RequestBody requestBody = new MultipartBody.Builder()
                    .setType(MultipartBody.FORM)
                    .addFormDataPart("file", encodeToBase64(image,  Bitmap.CompressFormat.JPEG, 100))
                    .addFormDataPart("email", "email@gmail.com")

                    .build();

            Request request = new Request.Builder()
                    .url(link)
                    .post(requestBody)
                    .build();
            Call call = client.newCall(request);
            Response response = call.execute();
            return response.body().string();
        } catch (Exception e) {
            System.out.println(e.getMessage());
        }
        return "Error";
    }

    public static String encodeToBase64(Bitmap image, Bitmap.CompressFormat compressFormat, int quality)
    {
        ByteArrayOutputStream byteArrayOS = new ByteArrayOutputStream();
        image.compress(compressFormat, quality, byteArrayOS);
        return Base64.encodeToString(byteArrayOS.toByteArray(), Base64.NO_WRAP);
    }

    protected void onPostExecute(String result) {
        System.out.println(result);
        try {
            if (result.equals("Good to go")) {
                Toast.makeText(this.context,"You face has been successfully updated.",Toast.LENGTH_SHORT).show();
            } else {
                Toast.makeText(this.context,"No face was detected. Try another picture.",Toast.LENGTH_SHORT).show();
            }
        } catch (Exception e) {
            System.out.println(e.getMessage());
            Toast.makeText(this.context,"An error occurred with your upload",Toast.LENGTH_SHORT).show();
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
}
