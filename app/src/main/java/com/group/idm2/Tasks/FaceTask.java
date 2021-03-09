package com.group.idm2.Tasks;

import android.content.Context;
import android.graphics.Bitmap;
import android.util.Base64;
import java.io.ByteArrayOutputStream;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;

public class FaceTask extends AbstractTask {
    private Bitmap image;

    public FaceTask(Context context, String email, String password, Bitmap image) {
        super(context, email, password);
        this.image = image;
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", this.email)
                .addFormDataPart("password", this.getSHA(this.password))
                .addFormDataPart("image", "image")
                .build();
    }

    public static String encodeToBase64(Bitmap image, Bitmap.CompressFormat compressFormat, int quality)
    {
        ByteArrayOutputStream byteArrayOS = new ByteArrayOutputStream();
        image.compress(compressFormat, quality, byteArrayOS);
        return Base64.encodeToString(byteArrayOS.toByteArray(), Base64.NO_WRAP);
    }


}
