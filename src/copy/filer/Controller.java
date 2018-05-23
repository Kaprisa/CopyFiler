package copy.filer;

import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.AnchorPane;
import javafx.stage.DirectoryChooser;
import javafx.stage.Stage;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.nio.MappedByteBuffer;
import java.nio.channels.FileChannel;
import java.nio.file.*;
import java.nio.file.attribute.FileTime;
import java.util.ArrayList;
import java.util.stream.Stream;
import java.util.zip.CRC32;

public class Controller {

    @FXML
    private AnchorPane pane;

    @FXML
    private TextField dir1;

    @FXML
    private TextField dir2;

    @FXML
    private Button dir1Button;

    @FXML
    private Button dir2Button;

    @FXML
    private TreeView<MyPath> tree1;

    @FXML
    private TreeView<MyPath> tree2;

    @FXML
    private CheckBox processEmbedded;

    @FXML
    private Button okButton;

    @FXML
    private CheckBox showSame;

    @FXML
    private CheckBox compareSame;

    @FXML
    private TextField chosenFile;

    @FXML
    private Button copyButton;

    @FXML
    private Button copyNew;

    @FXML
    private Button copyAll;

    @FXML
    private ImageView iv1;

    @FXML
    private ImageView iv2;

    @FXML
    private ImageView iv3;

    @FXML
    private ImageView iv4;

    @FXML
    private ImageView iv5;

    @FXML
    private ImageView iv6;

    private ArrayList<Path> newFiles;

    @FXML
    void initialize() {
        newFiles = new ArrayList<>();
        iv1.setImage(new Image("/copy/filer/assets/warning.png"));
        iv2.setImage(new Image("/copy/filer/assets/size.png"));
        iv3.setImage(new Image("/copy/filer/assets/schedule.png"));
        iv4.setImage(new Image("/copy/filer/assets/file_compare.png"));
        iv5.setImage(new Image("/copy/filer/assets/file.png"));
        iv6.setImage(new Image("/copy/filer/assets/folder.png"));
        okButton.setOnAction(event -> process());
        setShowDirectoryDialogAction(dir1Button, dir1, tree1);
        setShowDirectoryDialogAction(dir2Button, dir2, tree2);
        tree1.getSelectionModel().selectedItemProperty().addListener((v, oldValue, newValue) -> {
            try {
                chosenFile.setText(newValue.getValue().path.toString());
            } catch (NullPointerException e) {}
        });
        copyButton.setOnAction(e -> {
            try {
                copy(chosenFile.getText(), dir2.getText() + chosenFile.getText().replaceFirst(dir1.getText(), ""), false);
                buildTree(Paths.get(dir2.getText()), makeRoot(dir2, tree2));
                buildTree1(Paths.get(dir1.getText()), makeRoot(dir1, tree1), Paths.get(dir2.getText()));
            } catch (IOException e1) {
                e1.printStackTrace();
            }
        });
        copyNew.setOnAction(e -> {
            newFiles.forEach(i -> {
                try {
                    copy(i.toString(), dir2.getText(), true);
                } catch (IOException e1) {
                    e1.printStackTrace();
                }
            });
        });
        copyAll.setOnAction(e -> {
            String to = dir2.getText();
            try (Stream<Path> paths = Files.walk(Paths.get(dir1.getText()))) {
                paths.forEach(p -> {
                    try {
                        Files.copy(p, Paths.get(to + p.toString().replaceFirst(dir1.getText().toString(), "")), StandardCopyOption.REPLACE_EXISTING, StandardCopyOption.COPY_ATTRIBUTES);
                    } catch (IOException e1) {
                        //e1.printStackTrace();
                    }
                });
            } catch (IOException e1) {
                e1.printStackTrace();
            }
            buildTree(Paths.get(dir2.getText()), makeRoot(dir2, tree2));
            buildTree1(Paths.get(dir1.getText()), makeRoot(dir1, tree1), Paths.get(dir2.getText()));
        });
    }

    private void copy(String from, String to, boolean isCheckDir) throws IOException {
        if (Files.isRegularFile(Paths.get(from)) || isCheckDir) {
            Files.copy(Paths.get(from), Paths.get(to), StandardCopyOption.REPLACE_EXISTING, StandardCopyOption.COPY_ATTRIBUTES);
        } else {
            try (Stream<Path> paths = Files.walk(Paths.get(from))) {
                paths
                        .forEach(f -> {
                            try {
                                Files.copy(f, Paths.get(to + f.toString().replaceFirst(from, "")), StandardCopyOption.REPLACE_EXISTING, StandardCopyOption.COPY_ATTRIBUTES);
                            } catch (IOException e) {
                                //e.printStackTrace();
                            }
                        });
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

    private void setShowDirectoryDialogAction(Button button, TextField field, TreeView<MyPath> tree) {
        button.setOnAction(event -> {
            DirectoryChooser directoryChooser = new DirectoryChooser();
            Stage stage = (Stage) pane.getScene().getWindow();
            File selectedDirectory =
                    directoryChooser.showDialog(stage);
            if (selectedDirectory != null) {
                field.setText(selectedDirectory.getAbsolutePath());
                TreeItem<MyPath> root = makeRoot(field, tree);
                buildTree(Paths.get(field.getText()), root);
            }
        });
    }

    private void process() {
        buildTree1(Paths.get(dir1.getText()), makeRoot(dir1, tree1), Paths.get(dir2.getText()));
    }

    private TreeItem<MyPath> makeRoot(TextField field, TreeView<MyPath> tree) {
        MyPath myPath = new MyPath(Paths.get(field.getText()));
        TreeItem<MyPath> rootItem = new TreeItem<MyPath>(myPath);
        rootItem.setExpanded(true);
        tree.setRoot(rootItem);
        return rootItem;
    }

    private void buildTree1(Path path, TreeItem<MyPath> parent, Path path2) {
        try (Stream<Path> paths = Files.walk(path, 1)) {
            paths
                    .forEach(f -> {
                        if (f != path) {
                            String name = f.getFileName().normalize().toString();
                            TreeItem<MyPath> item = new TreeItem<MyPath>(new MyPath(f));
                            Path p2 = Paths.get(path2 + "/" + name);
                            boolean isSame = false;
                            if (Files.notExists(p2)) {
                                item.setGraphic(new ImageView(new Image("/copy/filer/assets/warning.png")));
                            } else {
                                try {
                                    String icon = "file";
                                    isSame = true;
                                    if (!Files.getAttribute(f, "size").equals(Files.getAttribute(p2, "size"))) {
                                        icon = "size";
                                        isSame = false;
                                    } else if (((FileTime)Files.getAttribute(f, "basic:creationTime")).compareTo((FileTime)Files.getAttribute(p2, "basic:creationTime")) != 0) {
                                        icon = "schedule";
                                        isSame = false;
                                    } else if (compareSame.isSelected() && Files.isRegularFile(f) && checksumMappedFile(f.toString()) != checksumMappedFile(p2.toString())) {
                                        icon = "file_compare";
                                        isSame = false;
                                    } else if (Files.isDirectory(f)) {
                                        icon = "folder";
                                    }
                                    if (isSame && ((FileTime)Files.getAttribute(f, "basic:creationTime")).compareTo((FileTime)Files.getAttribute(p2, "basic:creationTime")) > 0) {
                                        newFiles.add(f);
                                    }
                                    item.setGraphic(new ImageView(new Image("/copy/filer/assets/"+icon+".png")));
                                } catch (IOException e) {
                                    e.printStackTrace();
                                }
                            }
                            if (!(showSame.isSelected() && isSame)) {
                                if (processEmbedded.isSelected() && Files.isDirectory(f)) {
                                    buildTree1(f, item, Paths.get(path2 + "/" + name));
                                }
                                parent.getChildren().add(item);
                            }
                        }
                    });
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void buildTree(Path path, TreeItem<MyPath> parent) {
        try (Stream<Path> paths = Files.walk(path, 1)) {
            paths
                    .forEach(f -> {
                        TreeItem<MyPath> item = new TreeItem<MyPath>(new MyPath(f));
                        if (f != path) {
                            parent.getChildren().add(item);
                            if (Files.isDirectory(f)) {
                                buildTree(f, item);
                                item.setGraphic(new ImageView(new Image("/copy/filer/assets/folder.png")));
                            } else {
                                item.setGraphic(new ImageView(new Image("/copy/filer/assets/file.png")));
                            }
                        }
                    });
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static long checksumMappedFile(String filepath) throws IOException {
        FileInputStream inputStream = new FileInputStream(filepath);
        FileChannel fileChannel = inputStream.getChannel();
        int len = (int) fileChannel.size();
        MappedByteBuffer buffer = fileChannel.map(FileChannel.MapMode.READ_ONLY, 0, len);
        CRC32 crc = new CRC32();
        for (int cnt = 0; cnt < len; cnt++) {
            int i = buffer.get(cnt);
            crc.update(i);
        }
        return crc.getValue();
    }


    private static class MyPath {
        Path path;
        String name;
        String icon;

        MyPath(Path path) {
            this.path = path;
            this.name = path.getFileName().normalize().toString();
        }

        @Override
        public String toString() {
            return this.name;
        }
    }

}


