package com.group.idm2.Activities;

import android.Manifest;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;

import com.group.idm2.Tasks.FaceTask;
import com.group.idm2.R;

import androidx.annotation.NonNull;
import androidx.annotation.RequiresApi;

public class FaceActivity extends AbstractActivity {
    Button face;
    SharedPreferences sharedPreferences;
    private ProgressBar spinner;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_face);

        face = (Button)findViewById(R.id.face);
        spinner = (ProgressBar)findViewById(R.id.progressBar);
        spinner.setVisibility(View.GONE);

        sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);
    }

    private static final int MY_CAMERA_REQUEST_CODE = 100;

    @RequiresApi(api = Build.VERSION_CODES.M)
    public void openCamera(View view) {

        if (checkSelfPermission(Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED) {
            requestPermissions(new String[]{Manifest.permission.CAMERA}, MY_CAMERA_REQUEST_CODE);
        } else {
            spinner.setVisibility(View.VISIBLE);
            Intent send = new Intent(this, CameraActivity.class);
            this.startActivity(send);
        }
    }
    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == MY_CAMERA_REQUEST_CODE) {
            if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                showToast(this, "Camera Permission has been granted");
                Intent send = new Intent(this, CameraActivity.class);
                this.startActivity(send);
            } else {
               showToast(this, "Camera permission has been denied. You cannot access this feature without approving it.");
            }
        }
    }
}
