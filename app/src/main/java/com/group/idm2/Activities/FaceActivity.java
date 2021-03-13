package com.group.idm2.Activities;

import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.View;
import android.widget.Button;

import com.group.idm2.Tasks.FaceTask;
import com.group.idm2.R;

public class FaceActivity extends AbstractActivity {
    Button faceNormal, faceLeft, faceRight;
    SharedPreferences sharedPreferences;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_face);

        faceNormal = (Button)findViewById(R.id.faceNormal);
        faceLeft = (Button)findViewById(R.id.faceLeft);
        faceRight = (Button)findViewById(R.id.faceRight);

        sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);

    }

    public void openCamera(View view) {
        Intent camera_intent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        startActivityForResult(camera_intent, 10);
    }

    @Override
    protected void onActivityResult(int requestCode,
                                    int resultCode,
                                    Intent data) {

        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 10) {
            Bitmap photo = (Bitmap) data.getExtras()
                    .get("data");
            Bitmap image = photo;
            this.updateFace(image);
        }
    }

    public void updateFace(Bitmap image) {

        new FaceTask(this, this.sharedPreferences.getString("email", ""), this.sharedPreferences.getString("password", ""),image).execute();
    }
}
