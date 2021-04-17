package com.group.idm2.Activities;

import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.hardware.Camera;
import android.hardware.Camera.PictureCallback;
import android.hardware.Camera.ShutterCallback;
import android.os.Bundle;
import android.util.Log;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.group.idm2.R;
import com.group.idm2.Tasks.FaceTask;

public class CameraActivity extends AbstractActivity implements SurfaceHolder.Callback {
    TextView testView;

    Camera camera;
    SurfaceView surfaceView;
    SurfaceHolder surfaceHolder;

    PictureCallback rawCallback;
    ShutterCallback shutterCallback;
    PictureCallback jpegCallback;
    SharedPreferences sharedPreferences;

    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        System.out.println("on create");

        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_camera);
        sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);

        surfaceView = (SurfaceView) findViewById(R.id.surfaceView);
        surfaceHolder = surfaceView.getHolder();

        surfaceHolder.addCallback(this);
        surfaceHolder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);

        jpegCallback = new PictureCallback() {
            public void onPictureTaken(byte[] data, Camera camera) {
                System.out.println("picture taken");

                try {
                    Bitmap bitmap = BitmapFactory.decodeByteArray(data, 0, data.length);

                    Matrix matrix = new Matrix();
                    matrix.postRotate(270);
                    Bitmap altered_bitmap = Bitmap.createBitmap(bitmap, 0, 0, bitmap.getWidth(), bitmap.getHeight(), matrix, true);

                    new FaceTask(CameraActivity.this, sharedPreferences.getString("email", ""), sharedPreferences.getString("password", ""),altered_bitmap).execute();
                    showToast(getApplicationContext(), "Your capture is being processed. Please Wait...");
                    finish();
                } catch (Exception e) {
                    showToast(getApplicationContext(), "An error occurred when taking your picture. Please try again");
                    e.printStackTrace();
                }
            }
        };
    }

    public void captureImage(View v) throws IOException {
        System.out.println("capture");
        camera.takePicture(null, null, jpegCallback);
    }

    public void refreshCamera() {
        System.out.println("refresh");

        if (surfaceHolder.getSurface() == null) {

            return;
        }


        try {
            camera.stopPreview();
        } catch (Exception e) {

        }


        try {
            camera.setPreviewDisplay(surfaceHolder);
            camera.startPreview();
        } catch (Exception e) {

        }
    }

    public void surfaceChanged(SurfaceHolder holder, int format, int w, int h) {
        System.out.println("surface changed");
        refreshCamera();
    }

    public void surfaceCreated(SurfaceHolder holder) {
        System.out.println("surface created");

        try {

            camera = Camera.open(Camera.CameraInfo.CAMERA_FACING_FRONT);
            camera.setDisplayOrientation(90);
        } catch (RuntimeException e) {

            System.err.println(e);
            return;
        }
        Camera.Parameters param;
        param = camera.getParameters();


        param.setPreviewSize(352, 288);
        camera.setParameters(param);
        try {

            camera.setPreviewDisplay(surfaceHolder);
            camera.startPreview();
        } catch (Exception e) {

            System.err.println(e);
            return;
        }
    }

    public void surfaceDestroyed(SurfaceHolder holder) {
        // stop preview and release camera
        camera.stopPreview();
        camera.release();
        camera = null;
    }
}
