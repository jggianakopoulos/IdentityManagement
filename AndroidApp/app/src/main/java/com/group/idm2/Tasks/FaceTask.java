package com.group.idm2.Tasks;

import android.content.Context;
import android.graphics.Bitmap;
import android.util.Base64;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.File;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;

public class FaceTask extends AbstractTask {
    private Bitmap image;

    public FaceTask(Context context, String email, String password, Bitmap image) {
        super(context, email, password);
        this.image = image;
        this.script = "updateface";
        this.actionWord = "face update";
        this.goHome = false;
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", this.email)
                .addFormDataPart("password", this.password)
                .addFormDataPart("image", encodeToBase64(this.image))
                .build();
    }

    public static String encodeToBase64(Bitmap image)
    {
        ByteArrayOutputStream byteArrayOS = new ByteArrayOutputStream();
        image.compress(Bitmap.CompressFormat.PNG, 100, byteArrayOS);
        return Base64.encodeToString(byteArrayOS.toByteArray(), Base64.NO_WRAP);
    }

    protected void onPostExecute(String result) {
        super.onPostExecute(result);
        JSONObject json = null;
        try {
            json = new JSONObject(result);
            if (Integer.parseInt(json.getString("user_id")) > 0) {
                Toast.makeText(this.context,"Success! Your face capture can now be used to sign in.",Toast.LENGTH_SHORT).show();
            } else {
                Toast.makeText(this.context,"Error! Your face could not be detected.",Toast.LENGTH_SHORT).show();
            }
        } catch (JSONException e) {
            Toast.makeText(this.context,"Error! Your face could not be detected.",Toast.LENGTH_SHORT).show();
        }

    }


}
