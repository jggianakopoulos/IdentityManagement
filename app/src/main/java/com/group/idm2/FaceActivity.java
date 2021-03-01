package com.group.idm2;

import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.LayerDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;

import java.io.InputStream;

public class FaceActivity extends AbstractActivity {
    Button cameraButton;
    ImageView photoHolder;
    Bitmap currentImage;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_face);

        cameraButton = (Button)findViewById(R.id.cameraButton);
        photoHolder = (ImageView)findViewById(R.id.takenPicture);
    }

    public void openCamera(View view) {
        Intent camera_intent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        startActivityForResult(camera_intent, 10);
    }

    public void selectFromPhotos(View view) {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        startActivityForResult(Intent.createChooser(intent, "Select Picture"), 20);
        startActivityForResult(intent, 20);
    }


    public void clearPhoto(View view) {
        photoHolder.setImageResource(0);
    }

    @Override
    protected void onActivityResult(int requestCode,
                                    int resultCode,
                                    Intent data) {

        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 10) {

            Bitmap photo = (Bitmap) data.getExtras()
                    .get("data");
            currentImage = photo;
            photoHolder.setImageBitmap(photo);
        } else if (requestCode == 20) {
            try {
                Uri imageUri = data.getData();
                InputStream imageStream = getContentResolver().openInputStream(imageUri);
                Bitmap photo = BitmapFactory.decodeStream(imageStream);
                photoHolder.setImageBitmap(photo);
                currentImage = photo;
            } catch (Exception e) {
                this.showToast(this, "There was an error with the image you selected.");
            }
        }
    }

    public void updateFace(View view) {
        new ImageTask(this, currentImage).execute();
    }
}
