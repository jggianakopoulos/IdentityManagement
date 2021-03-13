package com.group.idm2.Tasks;

import android.content.Context;
import android.graphics.Bitmap;
import android.util.Base64;
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
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", this.email)
                .addFormDataPart("password", this.getSHA(this.password))
                .addFormDataPart("image", encodeToBase64(this.image))
                .build();
    }

    public static String encodeToBase64(Bitmap image)
    {
        ByteArrayOutputStream byteArrayOS = new ByteArrayOutputStream();
        image.compress(Bitmap.CompressFormat.PNG, 100, byteArrayOS);
        return Base64.encodeToString(byteArrayOS.toByteArray(), Base64.NO_WRAP);
    }


}