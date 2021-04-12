package com.group.idm2.Activities;

import android.app.Activity;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.view.View;

import com.group.idm2.R;
import com.mikepenz.materialdrawer.Drawer;
import com.mikepenz.materialdrawer.DrawerBuilder;
import com.mikepenz.materialdrawer.model.DividerDrawerItem;
import com.mikepenz.materialdrawer.model.PrimaryDrawerItem;
import com.mikepenz.materialdrawer.model.SecondaryDrawerItem;
import com.mikepenz.materialdrawer.model.interfaces.IDrawerItem;

import androidx.appcompat.widget.Toolbar;

public class DrawerActivity extends AbstractActivity {
    public String header;
    public Toolbar toolBar;

    protected void onCreate(Bundle savedInstanceState) {
        toolBar = (Toolbar) findViewById(R.id.toolbar);

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            toolBar.setTitle(header);
            setSupportActionBar(toolBar);
            getDrawer(this,toolBar);
        }
        super.onCreate(savedInstanceState);
    }

    public void getDrawer(final Activity activity, Toolbar toolbar) {
        PrimaryDrawerItem drawerEmptyItem= new PrimaryDrawerItem().withIdentifier(0).withName("");
        drawerEmptyItem.withEnabled(false);

        PrimaryDrawerItem faceUpdate = new PrimaryDrawerItem().withIdentifier(1)
                .withName("Update Face");
        PrimaryDrawerItem profileSettings = new PrimaryDrawerItem().withIdentifier(2)
                .withName("Profile Settings");
        PrimaryDrawerItem changePassword = new PrimaryDrawerItem().withIdentifier(3)
                .withName("Change Password");
        SecondaryDrawerItem logout = new SecondaryDrawerItem().withIdentifier(4)
                .withName("Logout");

        Drawer result = new DrawerBuilder()
                .withActivity(activity)
                .withToolbar(toolbar)
                .withActionBarDrawerToggle(true)
                .withActionBarDrawerToggleAnimated(true)
                .withCloseOnClick(true)
                .withSelectedItem(-1)
                .addDrawerItems(
                        drawerEmptyItem,
                        faceUpdate,
                        profileSettings,
                        changePassword,
                        new DividerDrawerItem(),
                        logout
                )
                .withOnDrawerItemClickListener(new Drawer.OnDrawerItemClickListener() {
                    @Override
                    public boolean onItemClick(View view, int position, IDrawerItem drawerItem) {
                        if (drawerItem.getIdentifier() == 1 && !(activity instanceof FaceActivity)) {
                            Intent intent = new Intent(activity, FaceActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 2 && !(activity instanceof ProfileActivity)) {
                            Intent intent = new Intent(activity, ProfileActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 3 && !(activity instanceof PasswordActivity)) {
                            Intent intent = new Intent(activity, PasswordActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 3 && !(activity instanceof PasswordActivity)) {
                            Intent intent = new Intent(activity, PasswordActivity.class);
                            view.getContext().startActivity(intent);
                        } else if (drawerItem.getIdentifier() == 4) {
                            signOut();
                        }
                        return true;
                    }
                })
                .build();
    }

}